<?php

namespace Iabdulqadeer\ThreeDSPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Iabdulqadeer\ThreeDSPay\Contracts\PaymentStorage;
use Stripe\Webhook;

/**
 * Handles Stripe webhooks.
 * Configure endpoint in Stripe Dashboard to POST to /{prefix}/webhook.
 */
class WebhookController extends Controller
{
    public function handle(Request $request, PaymentStorage $storage)
    {
        $payload      = $request->getContent();
        $sigHeader    = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            // Verify signature when secret is set; otherwise accept raw JSON (dev)
            $event = $endpointSecret
                ? Webhook::constructEvent($payload, $sigHeader, $endpointSecret)
                : json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            // Return 400 to signal Stripe the payload was invalid
            return response('Invalid payload', 400);
        }

        $type = $event->type ?? null;

        if ($type === 'payment_intent.succeeded') {
            $pi = $event->data->object;

            // Persist success (implement real storage in your app)
            $storage->store([
                'provider'        => 'stripe',
                'payment_intent'  => $pi->id ?? null,
                'amount_received' => $pi->amount_received ?? null,
                'currency'        => $pi->currency ?? null,
                'status'          => $pi->status ?? null,
            ]);
        }

        // Always 200 OK to acknowledge receipt
        return response('ok', 200);
    }
}
