<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payment Error</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-red-50 text-red-900 dark:bg-slate-900 dark:text-slate-100 flex items-center justify-center p-6">
  <div class="max-w-md w-full bg-white dark:bg-slate-950 p-6 rounded-2xl shadow ring-1 ring-red-200 dark:ring-red-900/40">
    <div class="flex items-center gap-2 mb-2">
      <span class="text-2xl">❌</span>
      <h1 class="text-2xl font-bold">Payment Failed</h1>
    </div>
    <p class="mt-1 text-slate-700 dark:text-slate-300">Something went wrong. Please try again.</p>

    <div class="mt-4 rounded-md border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 text-sm">
      Tip: ensure <code class="font-mono">hooks.stripe.com</code> is reachable and not blocked by DNS/AdBlock.
    </div>

    <a href="{{ route('threeds.checkout') }}"
       class="inline-flex items-center gap-2 mt-6 px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-black">
      ← Back to checkout
    </a>
  </div>
</body>
</html>
