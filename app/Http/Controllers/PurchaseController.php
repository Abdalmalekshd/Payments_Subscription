<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User_Product;
use Auth;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function PurchaseProductForOnce($id){


        $user = Auth::user();


        try {
            $product=Product::find($id);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $productName = $product->name;
            $userid = $user->id;

            $totalprice =$product->price;
            $two0 = "00";
            $total = "$totalprice$two0";

            $session = Session::create([
                'line_items'  => [
                    [
                        'price_data' => [
                            'currency'     => 'USD',
                            'product_data' => [
                                "name" => $productName,

                            ],
                            'unit_amount'  => $total,
                        ],
                        'quantity'   => 1,
                    ],

                ],
                'mode'        => 'payment',
                'metadata' => [
                            'user_id' => $user->id,
                            'Product_id' => $product->id,
                        ],
                'success_url' => route('Purchase.For.Once.Success') . '?session_id={CHECKOUT_SESSION_ID}&id=' . $user->id . '&product_id=' . $product->id,
                'cancel_url'  => route('Purchase.For.Once.Cancel'),
            ]);

            return redirect()->away($session->url);


        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return redirect()->route('home')->withErrors('Invalid request to Stripe: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors('An error occurred: ' . $e->getMessage());
        }


    }


    public function PurchaseProductForOnceSuccess(Request $request)
    {
        $userId = $request->get('id');
        $productId = $request->get('product_id');
        $sessionId = $request->query('session_id');
        $TotalPay=$request->total;


        // Set Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Retrieve the session from Stripe
            $session = Session::retrieve($sessionId);

            User_Product::create([
                'user_id'=>$userId,
                'product_id'=>$productId,
                'status'=>'Purchase For Once',
            ]);








            return redirect()->route('home')->with(['success'=> 'Product successfully Purchased!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error'=> 'Something went wrong please try agian later']);

           return redirect()->route('subscriptions')->withErrors('An error occurred: ' . $e->getMessage());
        }

    }

    public function  PurchaseProductForOnceCancel(){
        return Redirect()->route('home');
    }





}