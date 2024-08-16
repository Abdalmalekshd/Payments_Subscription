<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function products(){

        $product=Product::paginate(20);

        return view('Products',['products' => $product]);
    }
}