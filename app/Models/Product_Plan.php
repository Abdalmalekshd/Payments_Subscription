<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Plan extends Model
{
    use HasFactory;


    public $table='product_plans';

    public $guarded=[];


    public function productsplans(){
        return $this->belongsTo(Product::class);
        }


        public function plansproducts(){
            return $this->belongsTo(Plan::class);
            }
}
