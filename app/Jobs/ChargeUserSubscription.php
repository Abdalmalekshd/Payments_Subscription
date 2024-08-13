<?php

namespace App\Jobs;

use App\Models\Subscription;
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

        $stripeClient = new StripeClient($stripeSecretKey);

        try {
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
                $this->subscription->update([
                    'status' => 'active',
                    'current_period_start' => now(),
                    'current_period_end' => $this->subscription->plan_type === 'year' ? now()->addYear() : now()->addMonth(),
                ]);
                Log::info('Subscription charged successfully: ' . $this->subscription->id);
            } else {
                $this->subscription->update([
                    'status' => 'past_due',
                ]);
                Log::error('Payment failed for subscription: ' . $this->subscription->id);
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
        }

    }

}