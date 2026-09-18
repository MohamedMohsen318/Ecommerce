<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') &middot; {{ config('app.name') }}</title>

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
<body class="min-h-screen bg-slate-950 font-sans text-slate-800 antialiased">
    <main class="grid min-h-screen place-items-center bg-[radial-gradient(circle_at_top_left,rgba(37,99,235,0.28),transparent_34rem),linear-gradient(135deg,#020617_0%,#0f172a_52%,#1e293b_100%)] px-4 py-10">
        <div class="w-full max-w-md">
            <a href="{{ route('home') }}" class="mb-6 inline-flex items-center gap-3 text-white">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-blue-600 text-sm font-black shadow-lg shadow-blue-950/30">
                    {{ mb_substr(config('app.name'), 0, 1) }}
                </span>
                <span class="text-lg font-bold">{{ config('app.name') }} Admin</span>
            </a>

            <section class="rounded-2xl border border-white/10 bg-white p-6 shadow-2xl shadow-slate-950/30">
                @if (session('success'))
                    <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </div>
    </main>
</body>
</html>
