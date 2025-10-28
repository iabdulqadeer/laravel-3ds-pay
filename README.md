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
php artisan vendor:publish --provider="Abdul\ThreeDSPay\ThreeDSPayServiceProvider" --tag=three-ds-config
```

#### Publish views

```bash
php artisan vendor:publish --provider="Abdul\ThreeDSPay\ThreeDSPayServiceProvider" --tag=three-ds-views
```

#### Publish migrations (if any)

```bash
php artisan vendor:publish --provider="Abdul\ThreeDSPay\ThreeDSPayServiceProvider" --tag=three-ds-migrations
```

```bash
php artisan migrate
```

## 🧪 Environment Setup

#### Add your Stripe keys in .env:

```bash
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
```

## ⚙️ Configuration File

#### Once published, open:
config/three_ds_pay.php

```bash
return [
    'default' => env('THREEDS_DRIVER', 'stripe'),

    'routes' => [
        'prefix'     => env('THREEDS_PREFIX', 'pay'),
        'middleware' => ['web'],
    ],

    'webhooks' => [
        'queue' => env('THREEDS_WEBHOOK_QUEUE', 'default'),
    ],

    'defaults' => [
        'amount'   => env('THREEDS_AMOUNT', '49.00'),
        'currency' => env('THREEDS_CURRENCY', 'USD,EUR,GBP,INR'),
        'order_id' => env('THREEDS_ORDER_ID', 'INV-1001'),
    ],

    'drivers' => [
        'stripe' => [
            'secret'         => env('STRIPE_SECRET'),
            'publishable'    => env('STRIPE_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        'flutterwave' => [
            'secret' => env('FLW_SECRET'),
        ],
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
| `GET`  | `/pay/checkout` | Checkout page             |
| `POST` | `/pay/intent`   | Create payment intent     |
| `GET`  | `/pay/return`   | Stripe 3DS return handler |
| `GET`  | `/pay/result`   | Result page               |
| `POST` | `/pay/webhook`  | Stripe webhook listener   |


## 💳 Quick Start (Out of the Box)

Once installed, visit:

/pay/checkout

Your checkout page is ready instantly.
To customize it:

```bash
php artisan vendor:publish --tag=three-ds-views
```

This publishes to:

resources/views/vendor/three-ds/

##  🧱 Example Blade Usage
{{-- resources/views/vendor/three-ds/pages/checkout.blade.php --}}

```bash
@php
  $title   = 'Secure Checkout';
  $heading = 'Complete Payment';
  $amount  = config('three_ds_pay.defaults.amount');
  $curr    = config('three_ds_pay.defaults.currency');
  $order   = config('three_ds_pay.defaults.order_id');
@endphp

<x-three-ds::layout :title="$title" :heading="$heading" :order_id="$order">
  <div class="mx-auto max-w-xl">
    <form id="three-ds-form" class="space-y-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/70 p-5 shadow-sm">
      <x-three-ds::card-form
        :amount_decimal="$amount"
        :allowed_currencies="$curr"
        :currency_default="$curr"
        :order_id="$order"
        :intent_route="route('three-ds.intent')"
        :return_route="route('three-ds.return')"
        :result_route="route('three-ds.result')"
        :publishable="config('three_ds_pay.drivers.stripe.publishable')" />
    </form>
  </div>
</x-three-ds::layout>
```
🌗 The built-in layout includes a Dark/Light mode toggle with persistence.


##  🧾 3DS Return + Invoices

After 3D Secure authentication, Stripe redirects to /pay/return.
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
POST https://your-app.test/pay/webhook
```
Set your signing secret:
```bash
STRIPE_WEBHOOK_SECRET=whsec_xxx
```
Test locally using:
```bash
stripe listen --forward-to http://localhost/pay/webhook
```
## 🧭 Example Custom Route

    // routes/web.php
    use Illuminate\Support\Facades\Route;
    
    Route::get('/checkout', function () {
        return view('three-ds::pages.checkout', [
            'order_id' => config('three_ds_pay.defaults.order_id'),
            // Optional overrides:
            // 'amount_decimal' => '99.95',
            // 'currency_default' => 'EUR',
        ]);
    })->name('three-ds.checkout');

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
  "description": "Pluggable 3D Secure payments for Laravel (Stripe driver included).",
  "type": "library",
  "version": "0.1.0",
  "license": "MIT",
  "autoload": {
    "psr-4": {
      "Abdul\\ThreeDSPay\\": "src/"
    }
  },
  "require": {
    "php": ">=8.1",
    "illuminate/support": "^10.0|^11.0|^12.0",
    "stripe/stripe-php": "^15.0"
  },
  "extra": {
    "laravel": {
      "providers": ["Abdul\\ThreeDSPay\\ThreeDSPayServiceProvider"],
      "aliases": { "ThreeDS": "Abdul\\ThreeDSPay\\Facades\\ThreeDS" }
    }
  },
  "minimum-stability": "stable"
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
APP_URL=https://your-app.test

STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx

THREEDS_DRIVER=stripe
THREEDS_PREFIX=pay
THREEDS_AMOUNT=49.00
THREEDS_CURRENCY=USD,EUR,GBP,INR
THREEDS_ORDER_ID=INV-1001
```

routes/web.php

```bash
use Illuminate\Support\Facades\Route;

Route::get('/checkout', fn () => view('three-ds::pages.checkout'))->name('three-ds.checkout');
```

Run locally

```bash
php artisan serve
```

# Visit http://localhost:8000/pay/checkout


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