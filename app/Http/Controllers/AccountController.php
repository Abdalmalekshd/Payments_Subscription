<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;
use Log;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;
class AccountController extends Controller
{

    public function account(){


        $user=User::where('id',Auth::user()->id)->first();
        return view('User.account',compact('user'));
    }


    public function UpdateAccount(UserRequest $req){
        $user = User::find($req->id);

        if ($user && $req->id == Auth::user()->id) {
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);

            $user->save();


            Auth::guard()->login($user);



            return redirect()->route('home')->with(['success' => 'User account updated successfully']);
        }


    }


    public function subscriptions(){


        $plans=Plan::get();
        $subscription=Subscription::where('user_id',Auth::user()->id)->where('status','active')->first();
        return view('User.subscriptions',compact('plans','subscription'));
    }


    public function update_payments(){

            return view('User.Update_Payments');

    }

    public function updateCard(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.sk'));

        $user = Auth::user();

        try {
            // Create a PaymentMethod using the token
            $paymentMethod = PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'token' => $request->token,
                ],
            ]);


            $customer = Customer::retrieve($user->stripe_id);

            // Attach the PaymentMethod to the customer
            $paymentMethod->attach(['customer' => $user->stripe_id]);

            // Set the new PaymentMethod as the default payment method for the customer
            $customer->invoice_settings->default_payment_method = $paymentMethod->id;
            $customer->save();

            // Update user model with card details
            $user->pm_type = $paymentMethod->card->brand;
            $user->pm_last_four = $paymentMethod->card->last4;

            $user->card_expiration_month = $paymentMethod->card->exp_month;
            $user->card_expiration_year = $paymentMethod->card->exp_year;


            $user->trial_ends_at = $request->expiration_date;

            $user->save();

            Log::info('Card updated successfully for customer: ' . $customer->id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error updating card: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }






    public function receipts(){


    Stripe::setApiKey(config('services.stripe.sk'));


    $customerStripeId = Auth::user()->stripe_id;


    $stripeSubscriptions = StripeSubscription::all(['customer' => $customerStripeId]);

    $paymentData = [];
    foreach ($stripeSubscriptions->data as $subscription) {
        foreach ($subscription->items->data as $item) {

            $price = \Stripe\Price::retrieve($item->price->id);
            $product = \Stripe\Product::retrieve($price->product);

            $paymentData[] = [
                'amount' => number_format($price->unit_amount / 100, 2),
                'currency' => strtoupper($price->currency),
                'status' => $subscription->status,
                'plan_name' => $product->name,

                'start_date' => $subscription->current_period_start ? date('Y-m-d', $subscription->current_period_start) : 'N/A',
                'end_date' => $subscription->current_period_end ? date('Y-m-d', $subscription->current_period_end) : 'N/A',
            ];
        }
    }

    return view('User.receipts', ['payments' => $paymentData]);

    }

    public function cancel_sub(){
        return view('User.Cancel_Sub');
    }


}