<?php

namespace App\Jobs;

use App\Models\User_Product;
use Carbon\Carbon;

use Stripe\StripeClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class ProcessProductPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $userProduct;

    /**
     * Create a new job instance.
     *
     * @return void
     */
        public function __construct(User_Product $userProduct)
    {
        $this->userProduct = $userProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stripeSecretKey = config('services.stripe.sk');
        if (!$stripeSecretKey) {
            Log::error('Stripe secret key not configured.');
            return;
        }

        $stripeClient = new StripeClient($stripeSecretKey);

        try {
            // Retrieve the price details
            $priceId = $this->userProduct->product->stripe_one_time_price_id;
            if (!$priceId) {
                throw new \Stripe\Exception\InvalidArgumentException('Invalid price ID');
            }
            $price = $stripeClient->prices->retrieve($priceId);

            // Retrieve the customer's default payment method from Stripe
            $customerId = $this->userProduct->user->stripe_id;
            $customer = $stripeClient->customers->retrieve($customerId);

            // Ensure that the customer has a default payment method
            if (!$customer->invoice_settings->default_payment_method) {
                $paymentMethods = $stripeClient->paymentMethods->all([
                    'customer' => $customerId,
                    'type' => 'card',
                ]);

                if (count($paymentMethods->data) > 0) {
                    $paymentMethod = $paymentMethods->data[0]->id;

                    // Set the first payment method as the default
                    $stripeClient->customers->update($customerId, [
                        'invoice_settings' => [
                            'default_payment_method' => $paymentMethod,
                        ],
                    ]);
                } else {
                    throw new \Stripe\Exception\InvalidArgumentException('User does not have any payment methods set');
                }
            }

            // Use the default payment method for payment
            $paymentMethod = $customer->invoice_settings->default_payment_method;

            // Amount in cents
            $amountInCents = max($price->unit_amount, 50); // Minimum amount in cents

            // Create a PaymentIntent
            $paymentIntent = $stripeClient->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => $price->currency,
                'customer' => $customerId,
                'payment_method' => $paymentMethod,
                'off_session' => true,
                'confirm' => true,
            ]);

            // If the PaymentIntent succeeds, update the user's subscription details
            if ($paymentIntent->status === 'succeeded') {
                $newEndDate = Carbon::now();
                if ($this->userProduct->purchase_type === 'weekly') {
                    $newEndDate = $newEndDate->addWeek();
                } elseif ($this->userProduct->purchase_type === 'monthly') {
                    $newEndDate = $newEndDate->addMonth();
                } elseif ($this->userProduct->purchase_type === 'yearly') {
                    $newEndDate = $newEndDate->addYear();
                }

                // Update the current subscription status to expired
                $this->userProduct->update([
                    'status' => 'expired',
                ]);

                // Create a new subscription record
                User_Product::create([
                    'user_id' => $this->userProduct->user->id,
                    'product_id' => $this->userProduct->product_id,
                    'status' => 'Subscribe',
                    'purchase_type' => $this->userProduct->purchase_type,
                    'subscription_start_date' => Carbon::now(),
                    'subscription_end_date' => $newEndDate,
                ]);

                Log::info('Product purchase charged successfully: ' . $this->userProduct->id);
            } else {
                Log::error('Payment failed for product purchase: ' . $this->userProduct->id);
            }
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('General Error: ' . $e->getMessage());
        }
    }
}