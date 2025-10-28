<?php

namespace Iabdulqadeer\ThreeDSPay\Contracts;

/**
 * Contracts for persisting payments.
 * Swap this out in your app via service binding to store real records.
 */
interface PaymentStorage
{
    /**
     * Persist a successful payment payload.
     *
     * @param array<string,mixed> $data
     */
    public function store(array $data): void;
}
