<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payment Success</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-emerald-50 text-emerald-900 dark:bg-slate-900 dark:text-slate-100 flex items-center justify-center p-6">
  <div class="max-w-md w-full bg-white dark:bg-slate-950 p-6 rounded-2xl shadow ring-1 ring-emerald-200 dark:ring-emerald-900/40">
    <div class="flex items-center gap-2 mb-2">
      <span class="text-2xl">✅</span>
      <h1 class="text-2xl font-bold">Payment Successful</h1>
    </div>
    <p class="mt-1 text-slate-700 dark:text-slate-300">Your payment has been processed.</p>

    @if(!empty($invoiceUrl) || !empty($invoicePdf))
      <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
        @if(!empty($invoiceUrl))
          <a href="{{ $invoiceUrl }}" target="_blank" rel="noopener"
             class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
            View Invoice
          </a>
        @endif

        @if(!empty($invoicePdf))
          <a href="{{ $invoicePdf }}" target="_blank" rel="noopener"
             class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-black">
            Download PDF
          </a>
        @endif
      </div>
      <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
        Note: Invoice links appear only if this payment was created via Stripe Invoicing or a Payment Link that generates invoices.
      </p>
    @else
      <div class="mt-5 rounded-md border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 text-sm">
        No invoice available for this payment. (Invoices are only generated for certain Stripe flows.)
      </div>
    @endif

    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2 mt-6 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
      Go back to home →
    </a>
  </div>
</body>
</html>
