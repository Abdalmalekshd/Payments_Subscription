<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Subscription;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Stripe\Stripe;

class SubscriptionController extends Controller
{


    public function index()
    {
        $subscriptions = Subscription::where('user_id',Auth::user()->id)->with('customer', 'plan')->get();
        return view('User.Show_Subscriptions', compact('subscriptions'));
    }



    public function show($id)
    {
        $subscription = Subscription::with('customer', 'plan')->findOrFail($id);
        return view('User.show_invioce', compact('subscription'));
    }

    public function AddSubscriptionForm(){

        $data=[];

        $data['customer']=Customer::where('user_id',Auth::user()->id)->get();

        $data['plans']=Plan::where('user_id',Auth::user()->id)->get();


        return view('User.add_subscription',$data);
    }


    public function getPlanDetails($id)//هي الميثود مشان جيب انواع الخطة لما اليوزر يختار خطة من السيليكت اللي بفورم اضافة اشتراك
    {
    $plan = Plan::with('price')->where('user_id',Auth::user()->id)->find($id);


    if (!$plan) {
        abort(404,'Not Found');

    }

    $planPrices = $plan->price;

    $products = $plan->product->map(function($productPlan) {
        if ($productPlan->product) {
            return [
                'name' => $productPlan->product->name,
                'price' => $productPlan->product->price,
            ];
        } else {
            // يمكنك هنا التعامل مع الحالة عندما يكون المنتج غير موجود
            return [
                'name' => 'Unknown Product',
                'price' => 0,
            ];
        }
    });

    return response()->json([
        'plan' => $plan,
        'prices' => $planPrices,
        'products' => $products,
    ]);
    }



    public function create_subs(SubscriptionRequest $req){

        try{


            DB::beginTransaction();
            $planPrice=PlanPrice::find($req->plan_type);

            $originalPrice = $planPrice->price;
            $discount = $planPrice->discount;
            $discountType = $planPrice->discount_type;
            $discountLimit = $planPrice->discount_limit;

            $totalPrice = $originalPrice;


            if ($discount && $discountLimit && Carbon::now()->lessThanOrEqualTo($discountLimit)) {
                if ($discountType == 'percentage') {
                    $totalPrice -= $totalPrice * ($discount / 100);
                } elseif ($discountType == 'fixed') {
                    $totalPrice -= $discount;
                }
            }

            $existingpendingsubs=Subscription::where(function($q) use($req){
                $q->where('customer_id',$req->customer_id)->where('status','pending');
            })->first();
            // if($existingpendingsubs){
            //     $existingpendingsubs->delete();
            // }

            if(!$existingpendingsubs){

            Subscription::create([
                'user_id'       => Auth::id(),
                'customer_id'   => $req->customer_id,
                'plan_id'       => $req->plan_id,
                'price'         => $req->price,
                'status'        => 'pending',
                'plan_type'     =>$planPrice->plan_type

            ]);

        }else{
            return redirect()->back()->with(['error' => 'user already have pending subscription']);

        }
            DB::commit();
            return redirect()->back()->with(['success' => 'New Subscription Added']);


        }catch(\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with(['error' => 'try again later']);

        }


   }


   public function changestatus($id){
    $subscription=Subscription::where('user_id',Auth::user()->id)->find($id);
    if($subscription && $subscription->status == 'pending'){
        $subscription->update([
            'status'    => 'active',
        ]);
    }else{
        // return response()->json(['error' => 'Subscription not found'], 404);
        abort(404,'Not Found');
    }

    return redirect()->back()->with(['success' => 'Subscription Activated!']);


    }


}