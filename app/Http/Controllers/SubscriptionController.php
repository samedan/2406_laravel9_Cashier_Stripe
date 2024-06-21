<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Stripe\Plan;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    //

    public function showPlanForm() {
        return view('stripe.plans.create');
    }

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
                'product'=> [
                    'name' =>$request->name
                ]
                
            ]);
            // dd($plan);

            // enter into DBB
            \App\Models\Plan::create([
                'plan_id' => $plan->id,
                'name' => $plan->product,
                'price' => $plan->amount,
                'billing_method' => $plan->interval,
                'currency' => $plan->currency
                
            ]);

        //     'plan_id', 
        // 'name',
        // 'billing_method',
        // 'interval_count',
        // 'price',
        // 'currency'   

        //     "id" => "plan_QKjQ6Uuj3eWpfS"
        //     "object" => "plan"
        //     "active" => true
        //     "aggregate_usage" => null
        //     "amount" => 1000
        //     "amount_decimal" => "1000"
        //     "billing_scheme" => "per_unit"
        //     "created" => 1718962861
        //     "currency" => "eur"
        //     "interval" => "week"
        //     "interval_count" => 1
        //     "livemode" => false
        //     "metadata" => 
        // Stripe
        // \
        // StripeObject {#329 ▶}
        //     "meter" => null
        //     "nickname" => null
        //     "product" => "prod_QKjQMMzZ0866nU"
        //     "tiers" => null
        //     "tiers_mode" => null
        //     "transform_usage" => null
        //     "trial_period_days" => null
        //     "usage_type" => "licensed"

        }
        catch(Exception $ex) {
            dd($ex->getMessage());
        }

        return "success";
    }
        
}
