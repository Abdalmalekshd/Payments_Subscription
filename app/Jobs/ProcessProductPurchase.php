<?php

namespace App\Jobs;

use App\Models\User_Product;
use Carbon\Carbon;
use DB;
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

            DB::beginTransaction();

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

            $paymentMethod = $customer->invoice_settings->default_payment_method;

            // Select the appropriate price ID based on the purchase type
            $priceId = null;
            if ($this->userProduct->purchase_type === 'weekly') {
                $priceId = $this->userProduct->product->stripe_weekly_price_id;
            } elseif ($this->userProduct->purchase_type === 'monthly') {
                $priceId = $this->userProduct->product->stripe_monthly_price_id;
            } elseif ($this->userProduct->purchase_type === 'yearly') {
                $priceId = $this->userProduct->product->stripe_yearly_price_id;
            } else {
                $priceId = $this->userProduct->product->stripe_one_time_price_id;
            }

            if (!$priceId) {
                throw new \Stripe\Exception\InvalidArgumentException('Invalid price ID for the selected purchase type');
            }

            $price = $stripeClient->prices->retrieve($priceId);

            if ($this->userProduct->purchase_type === 'weekly' ||
                $this->userProduct->purchase_type === 'monthly' ||
                $this->userProduct->purchase_type === 'yearly') {
                // Handle recurring payment (Subscription)
                if ($price->type !== 'recurring') {
                    throw new \Stripe\Exception\InvalidArgumentException('The specified price is not recurring');
                }

                $subscription = $stripeClient->subscriptions->create([
                    'customer' => $customerId,
                    'items' => [['price' => $priceId]],
                    'default_payment_method' => $paymentMethod,
                ]);

                $newEndDate = Carbon::now();
                if ($this->userProduct->purchase_type === 'weekly') {
                    $newEndDate = $newEndDate->addWeek();
                } elseif ($this->userProduct->purchase_type === 'monthly') {
                    $newEndDate = $newEndDate->addMonth();
                } elseif ($this->userProduct->purchase_type === 'yearly') {
                    $newEndDate = $newEndDate->addYear();
                }

                // Update and create subscription record
                $this->userProduct->update([
                    'status' => 'expired',
                ]);

                User_Product::create([
                    'user_id' => $this->userProduct->user->id,
                    'product_id' => $this->userProduct->product_id,
                    'status' => 'Subscribe',
                    'purchase_type' => $this->userProduct->purchase_type,
                    'subscription_start_date' => Carbon::now(),
                    'subscription_end_date' => $newEndDate,
                    'subscription_id' => $subscription->id,
                ]);

                Log::info('Product subscription created successfully: ' . $this->userProduct->id);

            } else {
                // Handle one-time payment (PaymentIntent)
                if ($price->type !== 'one_time') {
                    throw new \Stripe\Exception\InvalidArgumentException('The specified price is not one-time');
                }

                $amountInCents = max($price->unit_amount, 50); // Minimum amount in cents

                $paymentIntent = $stripeClient->paymentIntents->create([
                    'amount' => $amountInCents,
                    'currency' => $price->currency,
                    'customer' => $customerId,
                    'payment_method' => $paymentMethod,
                    'off_session' => true,
                    'confirm' => true,
                ]);

                if ($paymentIntent->status === 'succeeded') {
                    // Process one-time purchase completion
                    $newEndDate = Carbon::now();
                    if ($this->userProduct->purchase_type === 'weekly') {
                        $newEndDate = $newEndDate->addWeek();
                    } elseif ($this->userProduct->purchase_type === 'monthly') {
                        $newEndDate = $newEndDate->addMonth();
                    } elseif ($this->userProduct->purchase_type === 'yearly') {
                        $newEndDate = $newEndDate->addYear();
                    }

                    $this->userProduct->update([
                        'status' => 'expired',
                    ]);

                    User_Product::create([
                        'user_id' => $this->userProduct->user->id,
                        'product_id' => $this->userProduct->product_id,
                        'status' => 'Subscribe',
                        'purchase_type' => $this->userProduct->purchase_type,
                        'subscription_start_date' => Carbon::now(),
                        'subscription_end_date' => $newEndDate,
                    ]);

                    DB::commit();

                    Log::info('Product purchase charged successfully: ' . $this->userProduct->id);
                } else {
                    Log::error('Payment failed for product purchase: ' . $this->userProduct->id);
                }
            }
        } catch (ApiErrorException $e) {
            DB::rollback();

            Log::error('Stripe API Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('General Error: ' . $e->getMessage());
        }

   }
}
