<nav class="border-b border-stone-200 bg-white px-4 py-3 sm:px-6 lg:px-8">
    <div class="mx-auto flex max-w-7xl items-center justify-between">
        <a href="{{ route('home') }}" class="font-display text-lg font-semibold">{{ config('app.name') }}</a>

        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('shop.index') }}" class="text-stone-600 hover:text-brand-600">Shop</a>
            <a href="{{ route('cart.index') }}" class="text-stone-600 hover:text-brand-600">Cart</a>

            @auth
                <span class="text-stone-600">أهلاً، {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-stone-600 hover:text-brand-600">تسجيل خروج</button>
                </form>
            @else
                <a href="{{ route('login.create') }}" class="text-stone-600 hover:text-brand-600">{{ __('auth.nav_login') }}</a>
                <a href="{{ route('register.create') }}" class="text-stone-600 hover:text-brand-600">{{ __('auth.nav_register') }}</a>
            @endauth
        </div>
    </div>
</nav>
