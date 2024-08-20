<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User_Product;
use Auth;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription;

class PurchaseController extends Controller
{
    public function SubscribePurchaseProduct(Request $req){

        $user = Auth::user();

        $product = Product::find($req->product_id);

        $purchaseType = $req->input('purchase_type'); // 'one_time', 'weekly', 'monthly', 'yearly'

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    // Create Stripe plans if they do not exist
    $product->createStripePlans();

    // Get the correct plan ID based on purchase type
    $priceId = null;
    switch ($purchaseType) {
        case 'one_time':
            $priceId = $product->stripe_one_time_price_id;
            break;
        case 'weekly':
            $priceId = $product->stripe_weekly_price_id;
            break;
        case 'monthly':
            $priceId = $product->stripe_monthly_price_id;
            break;
        case 'yearly':
            $priceId = $product->stripe_yearly_price_id;
            break;
    }

    if (!$priceId) {
        return response()->json(['error' => 'Invalid purchase type'], 400);
    }

    // Initialize Stripe client
    Stripe::setApiKey(config('services.stripe.sk'));

    try {
        // Create Stripe customer if not exists
        if (!$user->stripe_id) {
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
            ]);
            $user->stripe_id = $customer->id;
            $user->save();
        }

        // Create Checkout Session
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'mode' => $purchaseType == 'one_time' ? 'payment' : 'subscription',
            'success_url' => route('Purchase.Success') . '?session_id={CHECKOUT_SESSION_ID}&product_id=' . $product->id . '&status=' . $purchaseType,
            'cancel_url' => route('Purchase.Cancel'),
            'customer' => $user->stripe_id,
            'metadata' => [
                'purchase_type' => $purchaseType, // Add the purchase type to metadata
            ],
        ]);

        return redirect()->away($session->url);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }

    }

    public function PurchaseProductSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        $productId = $request->query('product_id');
        $status = $request->query('status');

        $user = Auth::user();

        // Verify the session here
        Stripe::setApiKey(config('services.stripe.sk'));
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        // Check if session contains subscription ID
        $subscriptionId = $session->subscription ?? null;

        if (!$subscriptionId && $status !== 'one_time') {
            return redirect()->route('home')->withErrors('No subscription found in session.');
        }


        // Retrieve the product and user details
        $product = Product::find($productId);

        if ($product) {
            $subscriptionStartDate = now();
            $subscriptionEndDate = match ($status) {
                'weekly' => now()->addWeek(),
                'monthly' => now()->addMonth(),
                'yearly' => now()->addYear(),
                default => null
            };

            $userProduct=User_Product::where('product_id' , $productId)->where('user_id',$user->id)->where('status','Subscribe')->first();
            if($userProduct){
                $userProduct->update([
                    'status'=>'expired'
                ]);
            }
            // Add or update record in user_products table
              User_Product::Create(

                [
                    'product_id' => $productId, 'user_id' => $user->id,
                    'subscription_id' => $subscriptionId,
                    'status' => ($status === 'one_time') ? 'Purchased For One Time' : 'Subscribe',
                    'purchase_type' => $status,
                    'subscription_start_date' => ($status === 'one_time') ? null : $subscriptionStartDate,
                    'subscription_end_date' => ($status === 'one_time') ? null : $subscriptionEndDate,
                ]
            );

            return redirect()->route('home')->with(['success' => 'Product successfully purchased!']);
        }

        return redirect()->route('home')->withErrors('Product not found.');

    }


public function PurchaseProductCancel()
{
    return redirect()->route('home')->with(['error' => 'Payment was cancelled.']);
}





}