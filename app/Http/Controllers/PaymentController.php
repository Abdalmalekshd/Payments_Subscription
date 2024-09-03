<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChoosePlanRequest;
use App\Models\Customer;
use App\Models\Subscription;
use Stripe\Refund;
use Stripe\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Subscription as StripeSubscription;
use Stripe\Stripe;
use App\Models\Plan;
use App\Models\PlanPrice;
use Carbon\Carbon;
use Stripe\Checkout\Session as StripeSession;
use Log;

class PaymentController extends Controller
{



    public function EmailtoProcessPayment(Request $request)

    {
        return view('User.startpaymentmethod');
    }

    public function processPayment(Request $request)
{
    $email = $request->input('email');

    $customer = Customer::where('email', $email)->first();


    if (!$customer) {
        return redirect()->back()->withErrors('This email is not associated with any account.');
    }

    $subscription=Subscription::where('customer_id',$customer->id)->first();



    if(!$subscription){
        return redirect()->back()->withErrors('This email is not associated with any account.');

    }

    return $this->checkout($customer->id);

}

public function checkout($customer_id)
{
    try {
        $customer = Customer::findOrFail($customer_id);
        $subscription = Subscription::where('customer_id', $customer_id)->firstOrFail();
        $plan = Plan::findOrFail($subscription->plan_id);

        if ($plan) {
            Stripe::setApiKey(config('services.stripe.sk'));

            if (!$customer->stripe_id) {
                $stripeCustomer = \Stripe\Customer::create([
                    'email' => $customer->email,
                ]);
                $customer->stripe_id = $stripeCustomer->id;
                $customer->save();
            }

            $price = $subscription->price;

            $planprice = PlanPrice::where(function($query) use($subscription, $plan) {
                $query->where('plan_type', $subscription->plan_type)
                      ->where('plan_id', $plan->id);
            })->first();

            if ($planprice && $planprice->discount && $planprice->discount_limit >= now()) {
                if ($planprice->discount_type === 'fixed') {
                    $price -= $planprice->discount;
                } elseif ($planprice->discount_type === 'percentage') {
                    $discountAmount = $price * ($planprice->discount / 100);
                    $price -= $discountAmount;
                }
            }

            $priceInCents = max($price * 100, 50); // تحويل السعر إلى سنتات وضمان أن يكون السعر على الأقل 50 سنتًا


            $stripePrice = \Stripe\Price::create([
                'unit_amount' => $priceInCents,
                'currency' => $customer->currency,
                'recurring' => [
                    'interval' => $subscription->plan_type, // 'day', 'week', 'month', 'year'
                ],
                'product_data' => [
                    'name' => $plan->name,
                ],
            ]);

            $subscription->stripe_price_id = $stripePrice->id;
            $subscription->save();

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $stripePrice->id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'customer' => $customer->stripe_id,
                'metadata' => [
                    'customer_id' => $customer->id,
                    'subscription_id' => $subscription->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'plan_type' => $subscription->plan_type,
                ],
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}&customer_id=' . $customer->id,
                'cancel_url' => route('cancel'),
            ]);

            return redirect()->away($session->url);
        } else {
            return redirect()->back()->withErrors('Plan not found.');
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        return redirect()->back()->withErrors('An error occurred with Stripe: ' . $e->getMessage());
    } catch (\Exception $e) {
        return  $e->getMessage();
    }
}



        public function success(Request $request)
        {
            Stripe::setApiKey(config('services.stripe.sk'));

            $customerid = $request->get('customer_id');
            // $planId = $request->get('plan_id');
            $sessionId = $request->query('session_id');
            // $price_id = $request->get('price_id');
            // $plan_type = $request->get('plan_type');

            try {

                $session = StripeSession::retrieve($sessionId);


                $subscriptionId = $session->subscription ?? null;

                if (!$subscriptionId) {
                    return redirect()->route('subscriptions.show')->withErrors('No subscription found in session.');
                }


                $stripeSubscription = StripeSubscription::retrieve($subscriptionId);

                if (!$stripeSubscription) {
                    return redirect()->route('subscriptions.show')->withErrors('Failed to retrieve subscription details.');
                }


                $currentPeriodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start);
                $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);


                $subscription = Subscription::where('customer_id', $customerid)->where('status','pending')->first();

                if ($subscription) {

                    $subscription->update([
                        'status' => 'active',
                        'subscription_id' => $subscriptionId,
                        'current_period_start' => $currentPeriodStart,
                        'current_period_end' => $currentPeriodEnd,
                    ]);
                }


                    // $subscription = Subscription::create([
                    //     'user_id' => $userId,
                    //     'subscription_id' => $subscriptionId,
                    //     'stripe_price_id' => $price_id,
                    //     'status' => $stripeSubscription->status,
                    //     'plan_id' => $planId,
                    //     'plan_type' => $plan_type,
                    //     'current_period_start' => $currentPeriodStart,
                    //     'current_period_end' => $currentPeriodEnd,
                    // ]);


                return redirect()->route('subscriptions.show')->with(['success' => 'Subscription successfully updated!']);
            } catch (\Exception $e) {
                return redirect()->back()->with(['error' => 'Something went wrong. Please try again later.']);
            }
    }

    public function  cancel(){
        return Redirect()->route('subscriptions.show');
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
