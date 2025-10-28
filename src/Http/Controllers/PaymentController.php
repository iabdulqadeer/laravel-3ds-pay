<?php

namespace Iabdulqadeer\ThreeDSPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Iabdulqadeer\ThreeDSPay\Support\StripeClientFactory;

/**
 * Creates a PaymentIntent with Automatic Payment Methods enabled.
 * The client confirms it with stripe.js (which will require 3DS where needed).
 */
class PaymentController extends Controller
{
    public function createIntent(Request $request)
    {
        // Basic validation for amount/currency coming from the page
        $validated = $request->validate([
            'amount'   => ['required', 'numeric', 'min:0.5'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        $amount   = (float) $validated['amount'];
        $currency = strtolower($validated['currency']);

        // Stripe expects integer amount in the smallest currency unit (e.g., cents)
        $unitAmount = (int) round($amount * 100);

        $stripe = StripeClientFactory::make();

        // Create a PaymentIntent; client will confirm with Elements
        $pi = $stripe->paymentIntents->create([
            'amount'                     => $unitAmount,
            'currency'                   => $currency,
            'automatic_payment_methods'  => ['enabled' => true],
            // Optionally add 'metadata' => [...] to pass custom info
        ]);

        return response()->json([
            'clientSecret'   => $pi->client_secret,
            'paymentIntent'  => $pi->id,
        ]);
    }
}
