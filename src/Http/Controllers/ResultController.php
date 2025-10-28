<?php

namespace Iabdulqadeer\ThreeDSPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Iabdulqadeer\ThreeDSPay\Support\StripeClientFactory;

class ResultController extends Controller
{
    public function success(Request $request)
    {
        // ✅ This line goes here — at the top of the method
        // It checks for either ?pi= or Stripe's ?payment_intent= param
        $piId = (string) ($request->query('pi', $request->query('payment_intent', '')));

        $invoiceUrl = null;
        $invoicePdf = null;

        if ($piId !== '') {
            try {
                $stripe = StripeClientFactory::make();

                // Retrieve PI and expand invoice reference if it exists
                $pi = $stripe->paymentIntents->retrieve($piId, [
                    'expand' => ['invoice'],
                ]);

                $invoiceId = $pi->invoice ?? null;

                if ($invoiceId) {
                    $invoice = \is_object($invoiceId)
                        ? $invoiceId
                        : $stripe->invoices->retrieve($invoiceId);

                    $invoiceUrl = $invoice->hosted_invoice_url ?? null;
                    $invoicePdf = $invoice->invoice_pdf ?? null;
                }
            } catch (\Throwable $e) {
                // Fail silently (user still sees success page)
            }
        }

        return view('threeds::success', [
            'invoiceUrl' => $invoiceUrl,
            'invoicePdf' => $invoicePdf,
        ]);
    }

}
