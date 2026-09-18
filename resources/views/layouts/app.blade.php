<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('app.supported_locales.'.app()->getLocale().'.dir', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,rgba(20,184,166,0.14),transparent_32rem),linear-gradient(180deg,#f8fafc_0%,#f1f5f9_100%)]">
        @include('partials.user-nav')

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @auth
                @unless (auth()->user()->hasVerifiedEmail())
                    <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm">
                        <span>Please verify your email address.</span>

                        <a
                            href="{{ route('verification.notice') }}"
                            class="ml-1 font-medium underline hover:no-underline"
                        >
                            Click here
                        </a>
                    </div>
                @endunless
            @endauth

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
