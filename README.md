# 💳 Laravel 3DS Pay (Stripe)

> Elegant, pluggable **3-D Secure (3DS)** card payments for Laravel — with a built-in Stripe driver, responsive checkout UI, dark/light theme, and real-time Stripe invoice links.

[![Packagist Version](https://img.shields.io/packagist/v/iabdulqadeer/laravel-3ds-pay.svg?color=4f46e5&label=version)](https://packagist.org/packages/iabdulqadeer/laravel-3ds-pay)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/laravel-10%20|%2011%20|%2012-red.svg)](https://laravel.com)
[![Stripe](https://img.shields.io/badge/Stripe-3DS-blue.svg)](https://stripe.com/docs/payments/3d-secure)

---

## ✨ Overview

**Laravel 3DS Pay** provides a drop-in **3-D Secure payment** solution for Laravel applications using Stripe.  
It ships with a fully responsive **checkout UI**, integrated **Stripe Elements**, **theme toggle**, server-side **intent management**, **webhooks**, and **invoice display** — all prewired for production.

🧩 Works with **Laravel 10 / 11 / 12**  
💻 Requires **PHP 8.1+**

---

## 🚀 Features

✅ Ready-to-use checkout page using **Stripe Elements (3DS)**  
✅ **Automatic 3DS authentication** flow  
✅ **Light/Dark mode** toggle with persistence  
✅ Tailwind & Laravel Breeze compatible design  
✅ Configurable **amount**, **currency**, and **order id**  
✅ Built-in **webhook** & **return** route handlers  
✅ Displays **Stripe Hosted Invoice** & **PDF** links  
✅ No JS build — uses **Tailwind CDN** fallback  
✅ Extendable **driver architecture** (Stripe included by default)

---

## ⚙️ Installation

```bash
composer require iabdulqadeer/laravel-3ds-pay

```
## 🔧 Publish Configuration & Assets

#### Publish config

```bash
php artisan vendor:publish --provider="Iabdulqadeer\ThreeDSPay\Providers\ThreeDSPayServiceProvider" --tag=threeds-config --force
```

#### Publish views

```bash
php artisan vendor:publish --provider="Iabdulqadeer\ThreeDSPay\Providers\ThreeDSPayServiceProvider" --tag=threeds-views --force
```

#### Publish migrations (if any)

```bash
php artisan vendor:publish --provider="Iabdulqadeer\ThreeDSPay\Providers\ThreeDSPayServiceProvider" --tag=three-ds-migrations --force
```

```bash
php artisan migrate
```

## 🧪 Environment Setup

#### Add your Stripe keys in .env:

```bash
# -----------------------------
# Stripe API Credentials
# -----------------------------
STRIPE_PUBLIC=
STRIPE_SECRET=

# Optional (for verifying incoming webhooks)
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXXXXXXXX

# -----------------------------
# 3DS Package Settings
# -----------------------------
THREEDS_ROUTE_PREFIX=payments

# Default demo amount and currency for the package checkout page
THREEDS_DEMO_AMOUNT=9.99
THREEDS_DEMO_CURRENCY=usd

# Redirect URLs after success/cancel
THREEDS_SUCCESS_URL=/payments/success
THREEDS_CANCEL_URL=/payments/cancel
```

## ⚙️ Configuration File

#### Once published, open:
config/three-ds-pay.php

```bash
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

```

💡 Tips

You can pass decimal amounts (e.g. "19.99").

Currency can be a single code (USD) or multiple (USD,EUR,INR).

Override defaults per request in your controller.


## 🌐 Default Routes

Auto-registered (prefix = /pay):

| Method | URI             | Description               |
| ------ | --------------- | ------------------------- |
| `GET`  | `/payments/checkout` | Checkout page             |
| `POST` | `/payments/intent`   | Create payment intent     |
| `GET`  | `/payments/success`  | Stripe 3DS return handler |
| `GET`  | `/payments/error`    | Result page               |
| `POST` | `/payments/webhook`  | Stripe webhook listener   |


## 💳 Quick Start (Out of the Box)

Once installed, visit:

/pay/checkout

Your checkout page is ready instantly.
To customize it:

```bash
php artisan vendor:publish --provider="Iabdulqadeer\ThreeDSPay\Providers\ThreeDSPayServiceProvider" --tag=threeds-views --force
```

This publishes to:

resources/views/vendor/three-ds/

##  🧱 Example Blade Usage
{{-- resources/views/vendor/threeds/checkout.blade.php --}}
{{-- resources/views/vendor/threeds/success.blade.php --}}
{{-- resources/views/vendor/threeds/error.blade.php --}}


🌗 The built-in layout includes a Dark/Light mode toggle with persistence.


##  🧾 3DS Return + Invoices

After 3D Secure authentication, Stripe redirects to /payments/success.
The package processes and redirects to /pay/result showing:

✅ Payment status
🧾 Stripe Hosted Invoice link
📄 Stripe Invoice PDF link
💲 Amount (human readable)
🔖 Order / Intent / Payment IDs
    
 If invoices aren’t generated by your Stripe setup, these buttons are hidden automatically.

 ##  🪝 Webhooks

Tell Stripe to send webhooks to:
```bash
POST https://your-app.test/payments/webhook
```
Set your signing secret:
```bash
STRIPE_WEBHOOK_SECRET=whsec_xxx
```
Test locally using:
```bash
stripe listen --forward-to http://localhost/payments/webhook
```
## 🧭 Example Custom Route

    // routes/web.php
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

## 🔒 Security

Fully 3-D Secure compliant (Stripe confirmCardPayment)
CSRF-protected backend routes
Exposes only publishable keys to the browser
PCI-compliant Stripe Elements integration

## 🤝 Contributing

Pull Requests are welcome!
Follow PSR-12 and ensure all tests pass.

## 📜 License

MIT © Abdul

🧩 Publishing to Packagist (Full Guide)
🧱 Step 1 — GitHub Repository Setup

Create a public repository on GitHub, for example:
```bash
https://github.com/iabdulqadeer/laravel-3ds-pay
```

Inside your package directory:

```bash
git init
```
```bash
git add .
```
```bash
git commit -m "Initial release"
```
```bash
git branch -M main
```
```bash
git remote add origin https://github.com/iabdulqadeer/laravel-3ds-pay.git
```
```bash
git push -u origin main
```

## 🧾 Step 2 — Composer Config Validation

```bash
Ensure your composer.json looks like this:

{
  "name": "iabdulqadeer/laravel-3ds-pay",
  "description": "Stripe 3DS (SCA) one-time payments for Laravel apps.",
  "type": "library",
  "license": "MIT",
  "version": "0.1.0",
  "keywords": ["laravel", "stripe", "3ds", "sca", "payments"],
  "support": {
    "issues": "https://github.com/iabdulqadeer/laravel-3ds-pay/issues",
    "source": "https://github.com/iabdulqadeer/laravel-3ds-pay"
  },
  "require": {
    "php": ">=8.0",
    "illuminate/support": "^9.0|^10.0|^11.0|^12.0",
    "stripe/stripe-php": "^14.0"
  },
  "autoload": {
    "psr-4": {
      "Iabdulqadeer\\ThreeDSPay\\": "src/"
    }
  },
  "extra": {
    "laravel": {
      "providers": [
        "Iabdulqadeer\\ThreeDSPay\\Providers\\ThreeDSPayServiceProvider"
      ]
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Iabdulqadeer\\ThreeDSPay\\Tests\\": "tests/"
    }
  },
  "require-dev": {
    "phpunit/phpunit": "^9.6|^10.0|^11.0",
    "pestphp/pest": "^2.0|^3.0"
  },
  "minimum-stability": "stable",
  "prefer-stable": true
}

```

🏷 Step 3 — Tag and Push Version

```bash
git tag v0.1.0
```
```bash
git push --tags
```

📦 Step 4 — Submit to Packagist

Go to https://packagist.org/packages/submit
Paste your repository URL.
Click “Check” → then “Submit”.

Packagist will automatically sync new tags.
(Optional) Enable Auto-Update Hook from GitHub → Settings → Webhooks → Add hook.

## 🧰 Example Application Setup

.env
```bash
# -----------------------------
# Stripe API Credentials
# -----------------------------
STRIPE_PUBLIC=
STRIPE_SECRET=

# Optional (for verifying incoming webhooks)
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXXXXXXXX

# -----------------------------
# 3DS Package Settings
# -----------------------------
THREEDS_ROUTE_PREFIX=payments

# Default demo amount and currency for the package checkout page
THREEDS_DEMO_AMOUNT=9.99
THREEDS_DEMO_CURRENCY=usd

# Redirect URLs after success/cancel
THREEDS_SUCCESS_URL=/payments/success
THREEDS_CANCEL_URL=/payments/cancel
```

routes/web.php

```bash
use Illuminate\Support\Facades\Route;
use Iabdulqadeer\ThreeDSPay\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
```

Run locally

```bash
php artisan serve
```

# Visit http://localhost:8000/payments/checkout


Test Card (3DS)
```bash
4000 0027 6000 3184
```

(any future expiry & CVC)

🪄 Author

Abdul Qadeer
💼 Laravel Developer | 💳 Payment Integrations | 🚀 Open-Source Creator
📦 Packagist
 • 🐙 GitHub  
