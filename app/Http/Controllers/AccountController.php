<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Plan;
use App\Models\User;
use Hash;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class AccountController extends Controller
{

    public function account(){


        $user=User::where('id',Auth::user()->id)->first();
        return view('account',compact('user'));
    }


    public function UpdateAccount(UserRequest $req){

        $user=User::find(Auth::user()->id);

        $user->name = $req->name;
        $user->email=$req->email;
            $user->save();

        return redirect()->route('home')->with('success', 'User account updated successfully');


    }


    public function subscriptions(){


        $plans=Plan::get();
        return view('subscriptions',compact('plans'));
    }

    public function update_payments(){
        $stripe = new StripeClient(config('services.stripe.sk'));

        try {
            // Retrieve customer details
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

        } catch (\Stripe\Exception\ApiErrorException $e) {


            return response()->json(['error' => $e->getMessage()], 500);
        }


    }






    public function cancel_sub(){
        return view('Cancel_Sub');
    }


}
