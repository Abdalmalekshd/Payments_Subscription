<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Subscription;
use Auth;
use DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function AddCustomerForm(){
        $country=Country::get();
        return view('User.add_customer',compact('country'));
    }


    public function create_customer(CustomerRequest $req){


        $country=Country::find($req->country_id);



        $customer=Customer::create([
                'user_id'   =>Auth::user()->id,
                'name'      =>$req->name,
                'email'     =>$req->email,
                'phone'     =>$req->phone,
                'address'   =>$req->address,
                'country'   =>$country->id,
                'currency'  =>$country->currency_code
        ]);



        if($customer){

        return redirect()->back()->with(['success' => 'New customer added']);
    }else{
        return redirect()->back()->with(['error' => 'Some thing went wrong try again later!']);

    }



}


}