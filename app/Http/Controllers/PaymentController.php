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
use Log;

class PaymentController extends Controller
{

    public function checkout(ChoosePlanRequest $request)
    {
        $user = Auth::user();
        $plan_id = $request->get('selectedPlanid');
        $plan_type = $request->get('plan_type'); // 'daily', 'weekly', 'month', 'year'

        try {

            $plan = Plan::findOrFail($plan_id);
            if ($plan) {

               Stripe::setApiKey(config('services.stripe.sk'));


                if (!$user->stripe_id) {
                    $stripeCustomer = \Stripe\Customer::create([
                        'email' => $user->email,
                    ]);
                    $user->stripe_id = $stripeCustomer->id;
                    $user->save();
                }


                switch ($plan_type) {
                    case 'daily':
                        $price_id = $plan->daily_price_id;
                        break;
                    case 'weekly':
                        $price_id = $plan->weekly_price_id;
                        break;
                    case 'month':
                        $price_id = $plan->monthly_price_id;
                        break;
                    case 'year':
                        $price_id = $plan->yearly_price_id;
                        break;
                    default:
                        return redirect()->route('subscriptions')->withErrors('Invalid plan type selected.');
                }


                if (!$price_id) {
                    return redirect()->route('subscriptions')->withErrors('Invalid price ID.');
                }


                $session = StripeSession::create([
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
                return redirect()->back()->withErrors('Plan not found.');
            }
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return redirect()->route('home')->withErrors('Invalid request to Stripe: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

        public function success(Request $request)
        {
            Stripe::setApiKey(config('services.stripe.sk'));

            $userId = $request->get('id');
            $planId = $request->get('plan_id');
            $sessionId = $request->query('session_id');
            $price_id = $request->get('price_id');
            $plan_type = $request->get('plan_type');

            try {

                $session = StripeSession::retrieve($sessionId);


                $subscriptionId = $session->subscription ?? null;

                if (!$subscriptionId) {
                    return redirect()->route('subscriptions')->withErrors('No subscription found in session.');
                }


                $stripeSubscription = StripeSubscription::retrieve($subscriptionId);

                if (!$stripeSubscription) {
                    return redirect()->route('subscriptions')->withErrors('Failed to retrieve subscription details.');
                }


                $currentPeriodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start);
                $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);


                $subscription = Subscription::where('user_id', $userId)->where('status','active')->first();

                if ($subscription) {

                    $subscription->update([
                        'status' => 'expired',
                    ]);
                }


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
        Stripe::setApiKey(config('services.stripe.sk'));


        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)->where('status','active')->first();

        $subscription_id = $subscription->subscription_id;

        try {
            $StripeSubscription = StripeSubscription::retrieve($subscription_id);
            $StripeSubscription->pause_collection = ['behavior' => 'mark_uncollectible'];
            $StripeSubscription->save();


            $subscription->status = 'paused';
            $subscription->save();

            return redirect()->back();


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function resume(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.sk'));

        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)->where('status','paused')->first();

        $subscription_id = $subscription->subscription_id;


        try {
            $StripeSubscription = StripeSubscription::retrieve($subscription_id);
            $StripeSubscription->pause_collection = null;
            $StripeSubscription->save();


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
                 $subscription = Subscription::where('user_id', $user->id)->where('status','active')->first();

            if (!$subscription) {
                return redirect()->back()->withErrors('No subscription found.');
            }

            try {


            Stripe::setApiKey(config('services.stripe.sk'));


               $stripeSubscription = StripeSubscription::retrieve($subscription->subscription_id);


            $stripeSubscription->cancel();


            $invoice = Invoice::all([
                'subscription' => $subscription->subscription_id,
                'limit' => 1
            ])->data[0];

            if ($invoice) {


                    Refund::create([
                        'charge' => $invoice->charge,
                        'amount' => $invoice->amount_due // Amount to refund in cents
                    ]);


                    $subscription->status = 'canceled';
                    $subscription->save();
                    return redirect()->back()->with(['success'=> 'Your subscription has been canceled and a refund has been processed.']);


            } else {


                return redirect()->back()->withErrors('No invoice found to process refund.');
            }

        } catch (\Exception $e) {
            Log::error('Error updating card: ' . $e->getMessage());
            return redirect()->back()->with(['error'=> 'Something went wrong please try agian later']);

        }
    }

}