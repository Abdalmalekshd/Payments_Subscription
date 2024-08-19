<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\User_Product;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Stripe\Refund;
use Stripe\Invoice;

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


     public function cancel_user_subscription($id)
     {

             $user = User::find($id);
             $subscription = Subscription::where('user_id', $user->id)->first();

             if (!$subscription || $subscription->status === 'canceled') {
                 return redirect()->back()->withErrors('No subscription found.');
             }

             // Set Stripe secret key
             Stripe::setApiKey(env('STRIPE_SECRET'));

             // Retrieve the subscription from Stripe
             $stripeSubscription = StripeSubscription::retrieve($subscription->subscription_id);

             // Cancel the subscription immediately
             $stripeSubscription->cancel();

             // Retrieve the latest invoice to process a refund
             $invoice = Invoice::all([
                 'subscription' => $subscription->subscription_id,
                 'limit' => 1
             ])->data[0];

             if ($invoice) {
                 try {
                     // Create a refund
                     Refund::create([
                         'charge' => $invoice->charge,
                         'amount' => $invoice->amount_due // Amount to refund in cents
                     ]);

                     // Update the subscription status in your database
                     $subscription->status = 'canceled';
                     $subscription->save();
                     return redirect()->back()->with(['success'=> 'Subscription has been canceled and a refund has been processed.']);

                 } catch (\Exception $e) {
                     return redirect()->back()->with(['error'=> 'Something went wrong please try agian later']);
                 }
             } else {
                 return redirect()->back()->withErrors('No invoice found to process refund.');
             }


     }




     public function cancel_user_product_subscription($id)
     {
         try {
             // Find the user
             $user = User::findOrFail($id);

             // Find the subscription for the user
             $subscription = User_Product::where('user_id', $user->id)
                 ->where('status', 'Subscribe')
                 ->firstOrFail();

             // Check if the subscription ID is valid
             if (empty($subscription->subscription_id)) {
                 return redirect()->back()->withErrors('Invalid subscription ID.');
             }

             // Set Stripe secret key
             Stripe::setApiKey(env('STRIPE_SECRET'));

             // Retrieve the subscription from Stripe
             $stripeSubscription = StripeSubscription::retrieve($subscription->subscription_id);

             // Check if the subscription is valid
             if (!$stripeSubscription || $stripeSubscription->status !== 'active') {
                 return redirect()->back()->withErrors('Subscription not found or already canceled.');
             }

             // Cancel the subscription immediately
             $stripeSubscription->cancel();

             // Retrieve the latest invoice to process a refund
             $invoices = Invoice::all([
                 'subscription' => $subscription->subscription_id,
                 'limit' => 1
             ]);

             if (!empty($invoices->data)) {
                 $invoice = $invoices->data[0];

                 // Create a refund
                 Refund::create([
                     'charge' => $invoice->charge,
                     'amount' => $invoice->amount_due // Amount to refund in cents
                 ]);

                 // Update the subscription status in the database
                 $subscription->status = 'canceled';
                 $subscription->save();

                 return redirect()->back()->with(['success' => 'Subscription has been canceled and a refund has been processed.']);
             } else {
                 return redirect()->back()->withErrors('No invoice found to process refund.');
             }
         } catch (\Exception $e) {

             return redirect()->back()->with(['error' => 'Something went wrong. Please try again later.']);
         }
     }
}
