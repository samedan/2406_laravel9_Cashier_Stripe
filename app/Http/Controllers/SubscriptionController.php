<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use Stripe\Plan;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    //

    public function showPlanForm() {
        return view('stripe.plans.create');
    }


    // POST plan on stripe & BDD
    public function savePlan(Request $request)
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe::setApiKey(config('services.stripe.secret'));
        // return $request->all();
        $amount = ($request->amount)*100;
        
        try {
            $plan = Plan::create([
                'amount'=> $amount,
                // 'currency'=> 'eur',
                'currency'=> $request->currency,
                'interval'=> $request->billing_period,
                'interval_count'=> $request->interval_count,
                'product'=> [
                    'name' =>$request->name
                ]
                
            ]);
            // dd($plan);

            // enter into DBB
            \App\Models\Plan::create([
                'plan_id' => $plan->id,
                'name' => $request->name,
                'price' => $plan->amount,
                'billing_method' => $plan->interval,
                'currency' => $plan->currency,
                'interval_count' => $plan->interval_count                
            ]);

        }
        catch(Exception $ex) {
            dd($ex->getMessage());
        }

        return "success";
    }

    // Show confirmation of bought plans/subscriptions
    public function allPlans() {
        $basic = \App\Models\Plan::where('name', 'basic')->first();
        $professional = \App\Models\Plan::where('name', 'professional')->first();
        $enterprise = \App\Models\Plan::where('name', 'enterprise')->first();
        // dd ($basic);
        return view('stripe.plans', compact('basic', 'professional', 'enterprise'));
    }

    public function checkout($planId) {
        $plan = \App\Models\Plan::where('plan_id', $planId)->first();
        if( ! $plan) {
            return  back()->withErrors(['message' => 'Unable to locate the plan']);
        }
        // dd($plan);
        return view('stripe.plans.checkout', [
            'plan' => $plan,
            'intent' => auth()->user()->createSetupIntent()
            ]
        );
    }

    public function processPlan(Request $request) {
        // return $request->all();
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $paymentMethod = null;
        $paymentMethod = $request->payment_method;
        if($paymentMethod != null) // trial period
        {
            $paymentMethod = $user->addPaymentMethod($paymentMethod); // resources cannot be null
        }
        // hidden form input
        $planId = $request->plan_id;
        // dd($plan);
        try {
            // from cashier
                $user->newSubscription(
                    'default', $planId
                // )->create($request->paymentMethodId);
                )->create($paymentMethod != null ? $paymentMethod->id : '');
        } catch (Exception $ex) {
            return back()->withErrors([
                'error' => 'Unable to create subscription due to: '.$ex->getMessage()
            ]);
        }

        
        
        $request->session()->flash('alert-success', 'You are subscribed to this plan');
        // return to_route('plans.checkout', $plan->plan_id);
        return to_route('plans.checkout', $planId);
    }


    // View Subscriprions
    public function allSubscriptions() {
        // $subscriptions = auth()->user()->subscriptions;
        $subscriptions = Subscription::where('user_id', auth()->id())->get();
        // dd($subscriptions);
        return view('stripe.subscriptions.index', compact('subscriptions'));
        // , [
        //     'intent' => auth()->user()->createSetupIntent()
        // ]);
    }

    // Cancel subscription
    public function cancelSubscriptions(Request $request) {
        $subscriptionName = $request->subscriptionName;
        dd($subscriptionName);
        if($subscriptionName) {
            $user = auth()->user();
            $user->subscription($subscriptionName)->cancel(); 
            return 'Subscription is canceled.'; // 'sunscriptions' table , column 'ends_at'
        }
    }

    // Resume subscription
    // public function resumeSubscriptions(Request $request) {
    //     $subscriptionName = $request->subscriptionName;
    //     if($subscriptionName) {
    //         $user = auth()->user();
    //         $user->subscription($subscriptionName)->resume(); 
    //         return 'Subscription is resumed.'; // 'sunscriptions' table , column 'ends_at'
    //     }
    // }
    public function resumeSubscriptions(Request $request)
    {
        $user = auth()->user();
        $subscriptionName = $request->subscriptionName;
        dd($subscriptionName);
        if($subscriptionName){
            $user->subscription($subscriptionName)->resume();
            return 'subsc is resumed';
        }
    }
     
}
