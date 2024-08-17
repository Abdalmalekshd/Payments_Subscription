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
            $paymentMethod = $customer->invoice_settings->default_payment_method;

            // If no default payment method, check available payment methods
            if (!$paymentMethod) {
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

            // If the PaymentIntent succeeds, update the user's default payment method if not set
            if ($paymentIntent->status === 'succeeded') {
                $newEndDate = Carbon::now();
                if ($this->userProduct->purchase_type === 'weekly') {
                    $newEndDate = $newEndDate->addWeek();
                } elseif ($this->userProduct->purchase_type === 'monthly') {
                    $newEndDate = $newEndDate->addMonth();
                }

                $this->userProduct->update([
                    'status' => 'subscribe',
                    'subscription_start_date' => Carbon::now(),
                    'subscription_end_date' => $newEndDate,
                ]);

                // Update default payment method if it was not set
                if (!$customer->invoice_settings->default_payment_method) {
                    $stripeClient->customers->update($customerId, [
                        'invoice_settings' => [
                            'default_payment_method' => $paymentIntent->payment_method,
                        ],
                    ]);
                }

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