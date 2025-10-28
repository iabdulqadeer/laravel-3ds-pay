<?php

namespace Iabdulqadeer\ThreeDSPay\Support;

use Stripe\StripeClient;

/**
 * Centralizes creation of the Stripe client using package config.
 */
class StripeClientFactory
{
    public static function make(): StripeClient
    {
        $secret = (string) config('three-ds-pay.stripe.secret');

        // You can inject additional config (e.g., apiVersion) here if needed.
        return new StripeClient([
            'api_key' => $secret,
        ]);
    }
}
