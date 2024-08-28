<?php

namespace App\Http\Controllers;

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
        $plans=Plan::where('user_id',Auth::user()->id)->with('price')->get();
        return view('User.add_customer',compact('plans'));
    }


    public function create_customer(Request $req){

        try{
        DB::beginTransaction();

        $customer=Customer::create([
                'user_id' =>Auth::user()->id,
                'name' =>$req->name,
                'email' =>$req->email,
                'phone' =>$req->phone,
                'address' =>$req->address,
        ]);

        $planPrice = PlanPrice::with('plan')->findOrFail($req->price_id);
        Subscription::create([
            'user_id'    =>Auth::user()->id,
            'customer_id'=>$customer->id,
            'status'     =>'pending',
            'plan_id' => $planPrice->plan->id,
            'plan_type' => $planPrice->plan_type,
        ]);

        DB::Commit();

        return redirect()->back()->with(['success' => 'New Customer Added']);
    } catch (\Exception $ex) {

        DB::rollBack();
        return $ex;

        return redirect()->back()->with(['error' => 'Error while adding new plan']);

    }
}


}