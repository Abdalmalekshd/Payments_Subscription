<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\UplaodImageTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Stripe\StripeClient;
class AdminProductsController extends Controller
{
    use UplaodImageTraits;



    public function ShowProducts(){
        $product=Product::paginate(20);

        return view('Admin.Products',['products' => $product]);
    }



}
