<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\StripeClient;
class Product extends Model
{
    use HasFactory;


    public $fillable=['name','price','image','description','stripe_price_id','user_id','quantity'];




    public function product_UserProduct(){
        return $this->hasMany(User_Product::class);
        }

        public function plan(){
            return $this->hasMany(Product_Plan::class);
            }


}