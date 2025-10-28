<?php

namespace Iabdulqadeer\ThreeDSPay\Storage;

use Iabdulqadeer\ThreeDSPay\Contracts\PaymentStorage;

/**
 * No-op storage. Replace with your Eloquent storage in the host app.
 */
class NullPaymentStorage implements PaymentStorage
{
    public function store(array $data): void
    {
        // Intentionally do nothing.
        // In your app, bind PaymentStorage to a real implementation.
    }
}
