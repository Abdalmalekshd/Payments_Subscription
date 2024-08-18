<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User_Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function products(){

        $product=Product::paginate(20);

        return view('Products',['products' => $product]);
    }



    public function manageProductssubs(){

        $data=[];
        $data['User_products']=User_Product::get();


        return view('ManageSubs',$data);
     }
}