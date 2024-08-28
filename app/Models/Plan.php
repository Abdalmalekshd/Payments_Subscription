<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\Price;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'plan_description'

    ];


    protected $casts = [
        'features' => 'array', // Ensure features is cast to an array
    ];




    public function subscriptionplan(){
        return $this->hasMany(Subscription::class);
        }


        public function product(){
            return $this->hasMany(Product_Plan::class);
            }



            public function price(){
                    return $this->hasMany(PlanPrice::class);
            }


            public function User(){
                return $this->hasOne(User::class);
        }

}