@props(['title' => 'Secure Checkout'])

<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}" class="h-full antialiased" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Tailwind via CDN for package demo; host app can override with Vite --}}
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- Stripe.js v3 --}}
  <script src="https://js.stripe.com/v3/"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
  <!-- Page Shell -->
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="w-full border-b border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 backdrop-blur">
      <div class="mx-auto max-w-3xl px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold">3D</div>
          <div>
            <h1 class="text-lg font-semibold leading-tight">3D Secure Card Payment</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Powered by Stripe</p>
          </div>
        </div>

        <!-- Amount Capsule -->
        <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-2">
          <span class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Amount</span>
          <span class="font-semibold">{{ number_format($amount, 2) }}</span>
          <span class="text-xs uppercase">{{ $currency }}</span>
        </div>
      </div>
    </header>

    <!-- Main -->
    <main class="flex-1">
      <div class="mx-auto max-w-3xl p-4 sm:p-6">
        <div class="grid md:grid-cols-5 gap-6">
          <!-- Left: Form Card -->
          <div class="md:col-span-3">
            <div class="w-full bg-white dark:bg-slate-950 shadow-sm ring-1 ring-slate-200 dark:ring-slate-800 rounded-2xl p-6">
              <h2 class="text-base font-semibold mb-1">Card Details</h2>
              <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Enter test card to simulate SCA.</p>

              <form id="pay-form" class="space-y-5">
                <div id="card-element" class="p-3 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-900"></div>

                {{-- Keep amount/currency in DOM (can be overridden by parent app if needed) --}}
                <input type="hidden" id="amount" value="{{ $amount }}">
                <input type="hidden" id="currency" value="{{ $currency }}">

                <button id="pay-btn" type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium disabled:opacity-60 disabled:cursor-not-allowed transition">
                  <svg id="btn-spinner" class="h-5 w-5 animate-spin hidden" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
                  </svg>
                  <span id="btn-text">Pay now</span>
                </button>

                <div id="msg" class="text-sm min-h-5"></div>

                <p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-2">
                  <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M12 1.75a10.25 10.25 0 1 0 0 20.5 10.25 10.25 0 0 0 0-20.5Zm0 18.5a8.25 8.25 0 1 1 0-16.5 8.25 8.25 0 0 1 0 16.5Zm-.75-12.5h1.5v5.5h-1.5v-5.5Zm0 7h1.5v1.5h-1.5v-1.5Z"/></svg>
                  Payments are processed securely by Stripe. This demo uses Stripe test mode.
                </p>
              </form>
            </div>
          </div>

          <!-- Right: Info / Tips -->
          <div class="md:col-span-2">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/40 p-5 space-y-4">
              <div>
                <h3 class="text-sm font-semibold mb-1">Test Card (3DS)</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300">
                  Use the Stripe 3DS test card:<br>
                  <code class="font-mono text-[12px] bg-white/70 dark:bg-slate-800/70 px-2 py-1 rounded">4000 0027 6000 3184</code><br>
                  Any <strong>future</strong> expiry (e.g. 12/39) and any CVC (e.g. 123).
                </p>
              </div>

              <div class="text-xs text-slate-600 dark:text-slate-300">
                <h4 class="font-semibold mb-1">What happens?</h4>
                <ul class="list-disc ms-4 space-y-1">
                  <li>We create a PaymentIntent on the server.</li>
                  <li>Stripe may show a 3-D Secure challenge (iframe/redirect).</li>
                  <li>On success, you’ll be redirected to the success page.</li>
                </ul>
              </div>

              <div class="text-xs text-slate-600 dark:text-slate-300">
                <h4 class="font-semibold mb-1">Troubleshooting</h4>
                <ul class="list-disc ms-4 space-y-1">
                  <li>Ensure <code>hooks.stripe.com</code> isn’t blocked by DNS/AdBlock.</li>
                  <li>Check your system time is correct.</li>
                </ul>
              </div>
            </div>
          </div>
        </div> <!-- /grid -->
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400">
      <div class="mx-auto max-w-3xl px-4 py-4 flex items-center justify-between">
        <span>Demo checkout</span>
        <span>© {{ date('Y') }} – Stripe 3DS</span>
      </div>
    </footer>
  </div>

<script>
(() => {
  const stripe = Stripe(@json($stripePk));
  const elements = stripe.elements();
  const card = elements.create('card', { hidePostalCode: false });
  card.mount('#card-element');

  const form = document.getElementById('pay-form');
  const btn = document.getElementById('pay-btn');
  const btnText = document.getElementById('btn-text');
  const btnSpinner = document.getElementById('btn-spinner');
  const msg = document.getElementById('msg');
  const csrf = document.querySelector('meta[name="csrf-token"]').content;

  function setBusy(isBusy) {
    btn.disabled = isBusy;
    btnSpinner.classList.toggle('hidden', !isBusy);
    btnText.textContent = isBusy ? 'Processing…' : 'Pay now';
  }

  function note(text, tone = 'info') {
    const cls = tone === 'error'
      ? 'text-red-600 dark:text-red-400'
      : 'text-emerald-600 dark:text-emerald-400';
    msg.innerHTML = `<span class="${cls}">${text}</span>`;
  }

  async function post(url = '', data = {}) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error('Network error');
    return res.json();
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    setBusy(true); note('');

    try {
      const amount = parseFloat(document.getElementById('amount').value);
      const currency = document.getElementById('currency').value;

      // 1) Create PaymentIntent on the server
      const { clientSecret } = await post(@json(route('threeds.intent')), { amount, currency });

      // 2) Confirm on client.
      //    IMPORTANT: For full-page 3DS redirect flows, Stripe will redirect to return_url
      //    and append query params like ?payment_intent=pi_xxx. We handle that on the server.
      const successPath = @json(config('three-ds-pay.redirects.success'));
      const successBase = `${window.location.origin}${successPath}`;

      const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: { card },
        return_url: `${successBase}` // Stripe will append payment_intent on redirect flows
      });

      if (error) {
        note(error.message || 'Payment failed.', 'error');
        setBusy(false);
        return;
      }

      // 3) If no full redirect occurred and we have an immediate result:
      if (paymentIntent && paymentIntent.status === 'succeeded') {
        // Pass PI id so the success page can try to resolve invoice links
        window.location.href = `${successBase}?pi=${encodeURIComponent(paymentIntent.id)}`;
      } else {
        // For any other state, go to error page
        window.location.href = @json(route('threeds.error'));
      }
    } catch (e) {
      note(e.message || 'Something went wrong.', 'error');
      setBusy(false);
    }
  });
})();
</script>

</body>
</html>
