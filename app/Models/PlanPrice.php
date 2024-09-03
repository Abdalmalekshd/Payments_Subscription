<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    use HasFactory;
    protected $fillable=[
        'stripe_price_id',
        'price',
        'plan_type',
        'plan_id',
        'discount',
        'discount_limit',
        'discount_type'
    ];


    public function plan(){
        return $this->belongsTo(Plan::class);
    }

}