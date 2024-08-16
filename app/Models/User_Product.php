<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Product extends Model
{
    use HasFactory;


    public $fillable=['product_id','user_id','status'];




    public function Product(){
        return $this->belongsTo(Product::class);
        }



    public function User(){
        return $this->belongsTo(User::class);
        }

}