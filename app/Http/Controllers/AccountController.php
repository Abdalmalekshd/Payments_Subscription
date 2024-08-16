<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Plan;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;
use Log;
use Session;
use Stripe\StripeClient;

class AccountController extends Controller
{

    public function account(){


        $user=User::where('id',Auth::user()->id)->first();
        return view('account',compact('user'));
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
        return view('subscriptions',compact('plans'));
    }

    public function update_payments(){
        $stripe = new StripeClient(config('services.stripe.sk'));

        try {
            // Retrieve customer details
            if(Auth::user()->subscriptionplan){

             $customerId=User::find(Auth::user()->id)->stripe_id;

            $customer = $stripe->customers->retrieve($customerId);

            // Retrieve payment methods associated with the customer
            $paymentMethods = $stripe->paymentMethods->all([
                'customer' => $customerId,
                'type' => 'card',
            ]);

            // Check if there are any payment methods available
            if (count($paymentMethods->data) > 0) {
                // Get the first payment method (assuming there is at least one)
                     $paymentMethod = $paymentMethods->data[0];
                $card =$paymentMethod->card;


                // Prepare the response data
                $data ['response'] = [
                    'email' => $customer->email,
                    'payment_method' => $paymentMethod->type,
                    'card_brand' => $card->brand,
                    'card_last4' => $card->last4,
                    'card_expiration' =>  $card->exp_year,
                ];
            } else {
                $data ['response'] = [
                    'email' => $customer->email,
                    'payment_method' => 'No payment method found',
                    'card_brand' => '',
                    'card_last4' => '',
                    'card_expiration' => '',
                ];
            }

            return view('Update_Payments',$data);
        }

        return view('Update_Payments');

        } catch (\Stripe\Exception\ApiErrorException $e) {


            return response()->json(['error' => $e->getMessage()], 500);
        }


    }



    public function receipts(){
        Stripe::setApiKey(config('services.stripe.sk'));


        $customerStripeId=Auth::user()->stripe_id;

        $payments = PaymentIntent::all(['customer' => $customerStripeId]);

        $paymentData = [];
        foreach ($payments->data as $payment) {
            $paymentData[] = [
                'amount' => number_format($payment->amount / 100, 2),
                'currency' => strtoupper($payment->currency),
                'status' => $payment->status,
            ];
        }

        return view('receipts', ['payments' => $paymentData]);

    }


    public function cancel_sub(){
        return view('Cancel_Sub');
    }


}