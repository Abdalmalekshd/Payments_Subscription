<?php

namespace App\Jobs;

use App\Models\PlanPrice;
use App\Models\Product;
use App\Models\Product_Plan;
use App\Models\Subscription;
use DB;
use Stripe\StripeClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;class ChargeUserSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle()
{
    $stripeSecretKey = config('services.stripe.sk');
    if (!$stripeSecretKey) {
        Log::error('Stripe secret key not configured.');
        return;
    }

    $stripeClient = new \Stripe\StripeClient($stripeSecretKey);

    try {
        DB::beginTransaction();


        $subscriptionId = $this->subscription->subscription_id;
        $stripeSubscription = $stripeClient->subscriptions->retrieve($subscriptionId);

        // Retrieve associated products for the plan
        $products = Product_Plan::where('plan_id', $this->subscription->plan_id)->get();

        $amountInCents = 0;

        // Add each product's price to the total amount
        foreach ($products as $productPlan) {
            $product = Product::find($productPlan->product_id);
            $productPriceInCents = $product->price * 100; // Convert to cents
            $amountInCents += $productPriceInCents;
        }


        $planPrice = PlanPrice::where(function($query) {
            $query->where('plan_type', $this->subscription->plan_type)
                  ->where('plan_id', $this->subscription->plan_id);
        })->first();

        $amountInCents += $planPrice->price * 100;


        if ($planPrice && $planPrice->discount && $planPrice->discount_limit >= now()) {
            if ($planPrice->discount_type === 'fixed') {
                $discountAmountInCents = $planPrice->discount * 100; // Convert to cents
                $amountInCents = max($amountInCents - $discountAmountInCents, 50); // Apply fixed discount
            } elseif ($planPrice->discount_type === 'percentage') {
                $discountAmountInCents = $amountInCents * ($planPrice->discount / 100); // Apply percentage discount
                $amountInCents = max($amountInCents - $discountAmountInCents, 50); // Ensure at least 50 cents
            }
        }

        // Create a new price in Stripe with the calculated amount
        $newPrice = $stripeClient->prices->create([
            'unit_amount' => $amountInCents,
            'currency' => $stripeSubscription->currency,
            'recurring' => [
                'interval' => $this->subscription->plan_type, // 'day', 'week', 'month', 'year'
            ],
            'product' => $stripeSubscription->items->data[0]->price->product,
        ]);

        // Create a PaymentIntent with the new total price after discount
        $paymentIntent = $stripeClient->paymentIntents->create([
            'amount' => $amountInCents,
            'currency' => $stripeSubscription->currency,
            'customer' => $stripeSubscription->customer,
            'payment_method' => $stripeSubscription->default_payment_method,
            'off_session' => true,
            'confirm' => true,
        ]);

        if ($paymentIntent->status === 'succeeded') {
            // Update current subscription to expired
            $this->subscription->update([
                'status' => 'expired',
            ]);

            // Update the subscription in Stripe with the new price
            $stripeClient->subscriptions->update($subscriptionId, [
                'items' => [
                    [
                        'id' => $stripeSubscription->items->data[0]->id,
                        'price' => $newPrice->id, // Use the new price ID
                    ],
                ],
                'proration_behavior' => 'create_prorations',
            ]);


            $current_period_end = match ($this->subscription->plan_type) {
                'day' => now()->addDay(),
                'week' => now()->addWeek(),
                'month' => now()->addMonth(),
                default => now()->addYear(),
            };

            // Save the new subscription details in the database
            Subscription::create([
                'user_id' => $this->subscription->user->id,
                'customer_id' => $this->subscription->customer->id,
                'plan_id' => $this->subscription->plan_id,
                'price' => $amountInCents / 100, // Store the actual total price after discount
                'status' => 'active',
                'plan_type' => $this->subscription->plan_type,
                'current_period_start' => now(),
                'current_period_end' => $current_period_end,
                'subscription_id' => $this->subscription->subscription_id,
                'stripe_price_id' => $newPrice->id,
            ]);

            DB::commit();
            Log::info('Subscription charged and renewed successfully: ' . $this->subscription->id);

        } else {
            Log::error('Payment failed for subscription: ' . $this->subscription->id);
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        DB::rollback();
        Log::error('Stripe API Error: ' . $e->getMessage());
    }
}


}
