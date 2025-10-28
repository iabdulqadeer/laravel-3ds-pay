<?php

use Illuminate\Support\Facades\Route;
use Iabdulqadeer\ThreeDSPay\Http\Controllers\CheckoutController;
use Iabdulqadeer\ThreeDSPay\Http\Controllers\PaymentController;
use Iabdulqadeer\ThreeDSPay\Http\Controllers\WebhookController;
use Iabdulqadeer\ThreeDSPay\Http\Controllers\ResultController;

Route::middleware(['web'])
    ->prefix(config('three-ds-pay.route_prefix'))
    ->name('threeds.')
    ->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
        Route::post('/intent',   [PaymentController::class, 'createIntent'])->name('intent');

        // Success now goes through a controller that resolves invoice links
        Route::get('/success', [ResultController::class, 'success'])->name('success');

        Route::view('/error', 'threeds::error')->name('error');
        Route::post('/webhook', [WebhookController::class, 'handle'])->name('webhook');
    });
