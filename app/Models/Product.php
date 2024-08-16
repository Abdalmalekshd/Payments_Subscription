<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    public $fillable=['name','price','image','description'];




    public function product_UserProduct(){
        return $this->hasMany(User_Product::class);
        }


}