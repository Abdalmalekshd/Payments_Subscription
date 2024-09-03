<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompositeItem extends Model
{
    use HasFactory;


    protected $fillable=[
        'product_composite_id',
        'item_id',
        'qty',

    ];



    public function parentprodcut(){
    return $this->belongsTo(Product::class,'product_composite_id')->where('is_composite_product',1);
    }

    public function childprodcuts(){
        return $this->belongsTo(Product::class,'item_id')->where('is_composite_product',0);
        }

}
