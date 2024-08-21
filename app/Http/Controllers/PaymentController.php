<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChoosePlanRequest;
use App\Models\Subscription;
use Stripe\Refund;
use Stripe\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Subscription as StripeSubscription;
use Stripe\Stripe;
use App\Models\Plan;
use Carbon\Carbon;
use Stripe\Checkout\Session as StripeSession;
class PaymentController extends Controller
{

            public function checkout(ChoosePlanRequest $request)
            {
                $user = Auth::user();
                $plan_id = $request->get('selectedPlanid');
                $plan_type = $request->get('plan_type'); // 'month' or 'year'

                try {
                    // Retrieve plan from database
                    $plan = Plan::findOrFail($plan_id);
                    if ($plan) {
                        // Set Stripe secret key
                        Stripe::setApiKey(config('services.stripe.sk'));

                        // Create or retrieve Stripe customer
                        if (!$user->stripe_id) {
                            $stripeCustomer = \Stripe\Customer::create([
                                'email' => $user->email,
                            ]);
                            $user->stripe_id = $stripeCustomer->id;
                            $user->save();
                        }

                        // Determine the price ID based on plan type
                        $price_id = $plan_type == 'year' ? $plan->yearly_price_id : $plan->monthly_price_id;

                        // Check if price_id is valid and active
                        if (!$price_id) {
                            return redirect()->route('subscriptions')->withErrors('Invalid price ID.');
                        }

                        // Create a checkout session
                        $session = \Stripe\Checkout\Session::create([
                            'payment_method_types' => ['card'],
                            'line_items' => [[
                                'price' => $price_id,
                                'quantity' => 1,
                            ]],
                            'mode' => 'subscription',
                            'customer' => $user->stripe_id,
                            'metadata' => [
                                'user_id' => $user->id,
                                'plan_id' => $plan_id,
                                'plan_name' => $plan->name,
                                'plan_type' => $plan_type,
                            ],
                            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}&id=' . $user->id . '&email=' . urlencode($user->email) . '&plan_id=' . $plan_id . '&plan_name=' . urlencode($plan->name) . '&plan_type=' . $plan_type . '&price_id=' . $price_id,
                            'cancel_url' => route('cancel'),
                        ]);

                        return redirect()->away($session->url);
                    } else {
                        return redirect()->back();
                    }

                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return redirect()->route('home')->withErrors('Invalid request to Stripe: ' . $e->getMessage());
                } catch (\Exception $e) {
                    return redirect()->route('home')->withErrors('An error occurred: ' . $e->getMessage());
                }

        }

        public function success(Request $request)
        {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $userId = $request->get('id');
            $planId = $request->get('plan_id');
            $sessionId = $request->query('session_id');
            $price_id = $request->get('price_id');
            $plan_type = $request->get('plan_type');

            try {
                // Retrieve the session from Stripe
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                // Check if session contains subscription ID
                $subscriptionId = $session->subscription ?? null;

                if (!$subscriptionId) {
                    return redirect()->route('subscriptions')->withErrors('No subscription found in session.');
                }

                // Get subscription details from Stripe
                $stripeSubscription = \Stripe\Subscription::retrieve($subscriptionId);

                // Check if subscription object was successfully retrieved
                if (!$stripeSubscription) {
                    return redirect()->route('subscriptions')->withErrors('Failed to retrieve subscription details.');
                }

                // Get subscription details
                $currentPeriodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start);
                $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);

                // Check if subscription already exists
                $subscription = Subscription::where('user_id', $userId)->where('status','active')->first();

                if ($subscription) {
                    // Update existing subscription
                    $subscription->update([
                        'status' => 'expired',
                    ]);
                }

                    // Create a new subscription if it doesn't exist
                    $subscription = Subscription::create([
                        'user_id' => $userId,
                        'subscription_id' => $subscriptionId,
                        'stripe_price_id' => $price_id,
                        'status' => $stripeSubscription->status,
                        'plan_id' => $planId,
                        'plan_type' => $plan_type,
                        'current_period_start' => $currentPeriodStart,
                        'current_period_end' => $currentPeriodEnd,
                    ]);


                return redirect()->route('subscriptions')->with(['success' => 'Subscription successfully updated!']);
            } catch (\Exception $e) {
                return redirect()->back()->with(['error' => 'Something went wrong. Please try again later.']);
            }
    }

    public function  cancel(){
        return Redirect()->route('subscriptions');
    }




    public function pause(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));


        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)->first();

        $subscription_id = $subscription->subscription_id;

        try {
            $StripeSubscription = StripeSubscription::retrieve($subscription_id);
            $StripeSubscription->pause_collection = ['behavior' => 'mark_uncollectible'];
            $StripeSubscription->save();

            // Update your local database if needed
            $subscription->status = 'paused';
            $subscription->save();

            return redirect()->back();


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function resume(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)->first();

        $subscription_id = $subscription->subscription_id;


        try {
            $StripeSubscription = StripeSubscription::retrieve($subscription_id);
            $StripeSubscription->pause_collection = null;
            $StripeSubscription->save();

            // Update your local database if needed
            $subscription->status = 'active';
            $subscription->save();

            return redirect()->back();

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function cancel_subscription()
    {

            $user = Auth::user();
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
                    return redirect()->back()->with(['success'=> 'Your subscription has been canceled and a refund has been processed.']);

                } catch (\Exception $e) {
                    return redirect()->back()->with(['error'=> 'Something went wrong please try agian later']);

                }
            } else {
                return redirect()->back()->withErrors('No invoice found to process refund.');
            }


    }

}