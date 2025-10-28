<?php

namespace Iabdulqadeer\ThreeDSPay\Http\Controllers;

use Illuminate\Routing\Controller;

/**
 * Renders the hosted checkout page.
 * Amount and currency are read from config (no publicId in route).
 */
class CheckoutController extends Controller
{
    public function show()
    {
        $amount   = (float) config('three-ds-pay.demo.amount');
        $currency = (string) config('three-ds-pay.demo.currency');
        $stripePk = (string) config('three-ds-pay.stripe.public');

        return view('threeds::checkout', compact('amount', 'currency', 'stripePk'));
    }
}
