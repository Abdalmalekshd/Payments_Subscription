<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Product extends Model
{
    use HasFactory;

    public $table='user_products';

    public $fillable=['product_id','user_id','status','purchase_type','subscription_start_date','subscription_end_date'];




    public function Product(){
        return $this->belongsTo(Product::class);
        }



    public function User(){
        return $this->belongsTo(User::class);
        }

}