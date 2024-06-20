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
            Plan::create([
                'amount'=> $amount,
                // 'currency'=> 'eur',
                'currency'=> $request->currency,
                'interval'=> $request->billing_period,
                'product'=> [
                    'name' =>$request->name
                ]
                
            ]);
        }
        catch(Exception $ex) {
            dd($ex->getMessage());
        }

        return "success";
    }
        
}
