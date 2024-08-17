<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\StripeClient;
class Product extends Model
{
    use HasFactory;


    public $fillable=['name','price','image','description','stripe_one_time_price_id', 'stripe_weekly_price_id', 'stripe_monthly_price_id'];




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
        ]);
        $this->stripe_one_time_price_id = $oneTimePrice->id;
    }

    if (!$this->stripe_weekly_price_id) {
        $weeklyPrice = $stripe->prices->create([
            'unit_amount' => $this->price * 100,
            'currency' => 'usd',
            'recurring' => ['interval' => 'week'],
            'product_data' => ['name' => $this->name],
        ]);
        $this->stripe_weekly_price_id = $weeklyPrice->id;
    }

    if (!$this->stripe_monthly_price_id) {
        $monthlyPrice = $stripe->prices->create([
            'unit_amount' => $this->price * 100,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product_data' => ['name' => $this->name],
        ]);
        $this->stripe_monthly_price_id = $monthlyPrice->id;
    }

    $this->save();
}

}