<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Product;
use App\Models\Product_Plan;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Plan as StripePlan;
class PlansController extends Controller
{

    public function ShowPlans(){
        $plans = Plan::where('user_id',Auth::user()->id)->get();
        return view('User.ShowPlan', compact('plans'));
    }




    public function AddPlansForm(){
        $products = Product::where('user_id',Auth::user()->id)->get();
        return view('User.add_plans', compact('products'));
    }



    public function create_plan(Request $req) {

        try {

            DB::beginTransaction();

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $plan = Plan::create([
                'name' => $req->name,
                'user_id' => Auth::user()->id,
                'description' => $req->description,
            ]);

            foreach ($req->price_type as $index => $type) {
                $price = $req->price[$index];
                $discount = $req->discount[$index] ?? null;
                $discountLimit = $req->discount_limit[$index] ?? null;
                $discountType = $req->discount_type[$index] ?? null;


                $stripePlan = StripePlan::create([
                    'amount' => $price * 100, // Stripe يستخدم السنتات
                    'interval' => $type,
                    'currency' => 'usd', // يمكنك تغيير العملة حسب الحاجة
                    'product' => [
                        'name' => $req->name,
                    ],
                ]);


                PlanPrice::create([
                    'plan_id' => $plan->id,
                    'plan_type' => $type,
                    'price' => $price,
                    'discount' => $discount,
                    'discount_limit' => $discountLimit,
                    'discount_type' => $discountType,
                    'stripe_price_id' => $stripePlan->id,
                ]);
            }

            foreach ($req->items as $product) {

            Product_Plan::create([
                'plan_id'       =>  $plan->id,
                'product_id'    =>  $product['id'],
                'quantity'   =>  $product['quantity'],


            ]);
        }


            DB::commit();

            return redirect()->back()->with(['success' => 'New Plan Added']);
        } catch (\Exception $ex) {


            DB::rollBack();

            return redirect()->back()->with(['error' => 'Error while adding new plan']);
        }
    }

    public function edit_plan($id){

        $plan = Plan::where('user_id',Auth::user()->id)->with(['price', 'product'])->find($id);

        if (!$plan) {
            return redirect()->route('User.ShowPlan')->with('error', 'Plan not found');
        }

         $products = Product::where('user_id',Auth::user()->id)->get();

        return view('User.edit_plan',compact('plan','products'));
    }



    public function updatePlan(Request $req)
    {

        try {

            DB::beginTransaction();

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $plan = Plan::where('user_id', Auth::user()->id)->find($req->plan_id);



            if (!$plan) {
                return redirect()->route('User.ShowPlan')->with('error', 'Plan not found');
            }

            $plan->update([
                'name' => $req->name,
                'description' => $req->description,
            ]);


            PlanPrice::where('plan_id', $plan->id)->delete();

            foreach ($req->price_type as $index => $type) {
                $price = $req->price[$index];
                $discount = $req->discount[$index] ?? null;
                $discountLimit = $req->discount_limit[$index] ?? null;
                $discountType = $req->discount_type[$index] ?? null;


                $stripePlan = \Stripe\Price::create([
                    'unit_amount' => $price * 100,
                    'recurring' => [
                        'interval' => $type,
                    ],
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $req->name,
                    ],
                ]);


                PlanPrice::create([
                    'plan_id' => $plan->id,
                    'plan_type' => $type,
                    'price' => $price,
                    'discount' => $discount,
                    'discount_limit' => $discountLimit,
                    'discount_type' => $discountType,
                    'stripe_price_id' => $stripePlan->id,
                ]);
            }


            Product_Plan::where('plan_id', $plan->id)->delete();

            foreach ($req->items as $product) {
                Product_Plan::create([
                    'plan_id' => $plan->id,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                ]);
            }


            DB::commit();

            return redirect()->back()->with(['success' => 'Plan Updated Successfully']);
        } catch (\Exception $ex) {

            DB::rollBack();
            return redirect()->back()->with(['error' => 'Error while updating plan']);
}
}


public function deletePlan($id) {
    try {

        DB::beginTransaction();

        $plan = Plan::where('user_id', Auth::user()->id)->find($id);

        if (!$plan) {
            return redirect()->route('User.ShowPlan')->with('error', 'Plan not found');
        }

        $plan->delete();


        // \Stripe\Plan::retrieve($plan->stripe_price_id)->delete();

        DB::commit();

        return redirect()->back()->with(['success' => 'Plan Deleted Successfully']);
    } catch (\Exception $ex) {

        DB::rollBack();
        return $ex;
        return redirect()->back()->with(['error' => 'Error while deleting plan']);
    }
}


}
