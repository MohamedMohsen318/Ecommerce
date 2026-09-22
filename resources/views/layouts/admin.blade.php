<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('app.supported_locales.'.app()->getLocale().'.dir', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('admin.dashboard')) &middot; {{ config('app.name') }}</title>

    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            theme: { extend: { colors: { brand: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                        } } } },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-stone-100 font-sans text-stone-800 antialiased">

<div class="flex min-h-screen">
    @include('partials.admin-nav')

    <div class="flex-1">
        <header class="flex items-center justify-between border-b border-stone-200 bg-white px-6 py-4">
            <h1 class="text-lg font-semibold">@yield('title', __('admin.dashboard'))</h1>

            <div class="flex items-center gap-4">
                <form method="POST" action="{{ route('locale.update') }}">
                    @csrf
                    <select name="locale" onchange="this.form.submit()"
                            class="rounded-lg border border-stone-300 bg-white px-2 py-1 text-sm text-stone-600 focus:border-brand-500 focus:ring-brand-500">
                        @foreach (config('app.supported_locales') as $locale => $language)
                            <option value="{{ $locale }}" @selected(app()->getLocale() === $locale)>
                                {{ $language['native'] }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <span class="text-sm text-stone-500">{{ auth('admins')->user()?->name }}</span>
            </div>
        </header>

        <main class="p-6">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
