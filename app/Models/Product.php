<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\StripeClient;
class Product extends Model
{
    use HasFactory;


    public $fillable=['name','price','image','description','stripe_one_time_price_id', 'stripe_weekly_price_id', 'stripe_monthly_price_id','stripe_yearly_price_id'];




    public function product_UserProduct(){
        return $this->hasMany(User_Product::class);
        }



        public function createStripePlans()
        {
            $stripe = new StripeClient(config('services.stripe.sk'));

            if (!$this->stripe_one_time_price_id) {
                $oneTimePrice = $stripe->prices->create([
                    'unit_amount' => $this->price * 100,
                    'currency' => 'usd',
                    'product_data' => ['name' => $this->name],
                    'metadata' => [
                        'subscription_type' => 'one_time',
                    ],
                ]);
                $this->stripe_one_time_price_id = $oneTimePrice->id;
            }

            if (!$this->stripe_weekly_price_id) {
                $weeklyPrice = $stripe->prices->create([
                    'unit_amount' => $this->price * 100,
                    'currency' => 'usd',
                    'recurring' => ['interval' => 'week'],
                    'product_data' => ['name' => $this->name],
                    'metadata' => [
                        'subscription_type' => 'weekly',
                    ],
                ]);
                $this->stripe_weekly_price_id = $weeklyPrice->id;
            }

            if (!$this->stripe_monthly_price_id) {
                $monthlyPrice = $stripe->prices->create([
                    'unit_amount' => $this->price * 100,
                    'currency' => 'usd',
                    'recurring' => ['interval' => 'month'],
                    'product_data' => ['name' => $this->name],
                    'metadata' => [
                        'subscription_type' => 'monthly',
                    ],
                ]);
                $this->stripe_monthly_price_id = $monthlyPrice->id;
            }

            if (!$this->stripe_yearly_price_id) {
                $yearlyPrice = $stripe->prices->create([
                    'unit_amount' => $this->price * 100,
                    'currency' => 'usd',
                    'recurring' => ['interval' => 'year'],
                    'product_data' => ['name' => $this->name],
                    'metadata' => [
                        'subscription_type' => 'yearly',
                    ],
                ]);
                $this->stripe_yearly_price_id = $yearlyPrice->id;
            }

            $this->save();
        }
}