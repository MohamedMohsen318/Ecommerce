<!DOCTYPE html>

<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    ```
    <title>@yield('title', config('app.name'))</title>

    <script src="https://cdn.tailwindcss.com"></script>
    ```

</head>

<body class="min-h-screen bg-stone-50 font-sans text-stone-800 antialiased">

```
@include('partials.user-nav')

<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Email Verification Notice --}}

    @auth
        @unless (auth()->user()->hasVerifiedEmail())
            <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">

                <span>
                    Please verify your email address.
                </span>

                <a
                    href="{{ route('verification.notice') }}"
                    class="ml-1 font-medium underline hover:no-underline"
                >
                    Click here
                </a>

            </div>
        @endunless
    @endauth

    {{-- Success Message --}}

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Page Content --}}

    @yield('content')

</main>
```

</body>
</html>
