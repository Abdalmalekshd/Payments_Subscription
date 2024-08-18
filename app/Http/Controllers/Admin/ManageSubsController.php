<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User_Product;
use Illuminate\Http\Request;

class ManageSubsController extends Controller
{
    public function managesubs(){

        $data=[];
        $data['Subs']=Subscription::with('User','Plan')->get();


        return view('Admin.ManageSubs',$data);
     }




     public function manageProductssubs(){

        $data=[];
        $data['User_products']=User_Product::get();


        return view('Admin.ManageProductSubs',$data);
     }
}