<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
        'business_id',
        'phone',
        'password',
        'address',
        'country',
        'currency',
        'stripe_id'
    ];



    public function users(){
        return $this->belongsTo(User::class);
        }


        public function subscriptions()
        {
            return $this->hasOne(Subscription::class);
        }

    public function country(){

        return $this->belongsTo(Country::class);

    }
}