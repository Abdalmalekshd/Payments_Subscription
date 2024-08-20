<?php

namespace App\Jobs;

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
            // Retrieve the subscription from Stripe
            $subscriptionId = $this->subscription->subscription_id;
            $stripeSubscription = $stripeClient->subscriptions->retrieve($subscriptionId);

            // Retrieve the price details
            $priceId = $this->subscription->stripe_price_id; // Get the price_id from the subscription
            $price = $stripeClient->prices->retrieve($priceId);

            // Amount in cents
            $amountInCents = max($price->unit_amount, 50); // Minimum amount in cents

            // Create a PaymentIntent
            $paymentIntent = $stripeClient->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => $price->currency,
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

                // Create a new subscription
                $newSubscription = $stripeClient->subscriptions->create([
                    'customer' => $stripeSubscription->customer,
                    'items' => [['price' => $priceId]],
                    'default_payment_method' => $stripeSubscription->default_payment_method,
                ]);



                // Save the new subscription details in the database
                Subscription::create([
                    'user_id' => $this->subscription->user->id,
                    'plan_id' =>$this->subscription->plan_id,
                    'status' => 'active',
                    'plan_type' => $this->subscription->plan_type,
                    'current_period_start' => now(),
                    'current_period_end' => $this->subscription->plan_type === 'year' ? now()->addYear() : now()->addMonth(),
                    'subscription_id' => $newSubscription->id,
                    'stripe_price_id' => $priceId,
                ]);
                DB::commit();
                Log::info('Subscription charged and renewed successfully: ' . $newSubscription->id);
            } else {
                $this->subscription->update([
                    'status' => 'past_due',
                ]);
                Log::error('Payment failed for subscription: ' . $this->subscription->id);
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollback();
            Log::error('Stripe API Error: ' . $e->getMessage());
        }

    }

}