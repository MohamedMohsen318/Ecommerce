<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-stone-50 font-sans text-stone-800 antialiased">

<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    @yield('content')
</main>

</body>
</html>
