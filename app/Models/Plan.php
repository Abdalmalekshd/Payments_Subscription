<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'monthly_price_id', 'yearly_price_id', 'monthly_price', 'yearly_price', 'features'
    ];


    protected $casts = [
        'features' => 'array', // Ensure features is cast to an array
    ];




    public function subscriptionplan(){
        return $this->hasMany(Subscription::class);
        }

}
