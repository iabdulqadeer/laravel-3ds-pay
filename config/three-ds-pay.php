<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    | All package routes will be prefixed with this value.
    | Example: /payments/checkout, /payments/webhook
    */
    'route_prefix' => env('THREEDS_ROUTE_PREFIX', 'payments'),

    /*
    |--------------------------------------------------------------------------
    | Stripe API Keys
    |--------------------------------------------------------------------------
    | Set via .env. Public key is used client-side in the checkout view.
    */
    'stripe' => [
        'public' => env('STRIPE_PUBLIC', ''),
        'secret' => env('STRIPE_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Amount & Currency
    |--------------------------------------------------------------------------
    | Fixed checkout amount/currency for the demo page.
    | Amount is in decimal units (e.g., 9.99 USD).
    */
    'demo' => [
        'amount'   => (float) env('THREEDS_DEMO_AMOUNT', 9.99),
        'currency' => env('THREEDS_DEMO_CURRENCY', 'usd'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    | Where to send users after payment success / cancel/failure.
    */
    'redirects' => [
        'success' => env('THREEDS_SUCCESS_URL', '/payments/success'),
        'cancel'  => env('THREEDS_CANCEL_URL', '/payments/cancel'),
    ],
];
