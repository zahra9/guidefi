<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Stripe;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return Response
     */
    public function stripe()
    {


        return view('stripe');
    }

    /**
     * success response method.
     *
     * @return Response
     */
    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
            "amount" => 200 * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Test payment from td.com."
        ]);

        Session::flash('success', 'Payment successful!');

        return back();
    }
}