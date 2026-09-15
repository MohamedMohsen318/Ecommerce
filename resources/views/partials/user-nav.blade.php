
<nav class="border-b border-stone-200 bg-white px-4 py-3 sm:px-6 lg:px-8">
    <div class="mx-auto flex max-w-7xl items-center justify-between">

        <a
            href="{{ route('home') }}"
            class="font-display text-lg font-semibold text-stone-900"
        >
            {{ config('app.name') }}
        </a>


        <div class="flex items-center gap-4 text-sm">
            <form method="POST" action="{{ route('locale.update') }}">
                @csrf

                <select
                    name="locale"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-stone-300 bg-white px-2 py-1 text-sm text-stone-600 focus:border-brand-500 focus:ring-brand-500"
                    aria-label="Language"
                >
                    @foreach (config('app.supported_locales') as $locale => $language)
                        <option value="{{ $locale }}" @selected(app()->getLocale() === $locale)>
                            {{ $language['native'] }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- Shop --}}

            <a
                href="{{ route('shop.index') }}"
                class="text-stone-600 hover:text-blue-600"
            >
                Shop
            </a>


            {{-- Cart --}}

            <a
                href="{{ route('cart.index') }}"
                class="text-stone-600 hover:text-blue-600"
            >
                Cart
            </a>


            @auth

                {{-- Wishlist --}}

                <a
                    href="{{ route('wishlist.index') }}"
                    class="text-stone-600 hover:text-blue-600"
                >
                    Wishlist
                </a>


                {{-- User --}}

                <span class="text-stone-600">
                    أهلاً، {{ auth()->user()->name }}
                </span>


                {{-- Logout --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="text-stone-600 hover:text-blue-600"
                    >
                        تسجيل خروج
                    </button>
                </form>

            @else

                {{-- Login --}}

                <a
                    href="{{ route('login.create') }}"
                    class="text-stone-600 hover:text-blue-600"
                >
                    {{ __('auth.nav_login') }}
                </a>


                {{-- Register --}}

                <a
                    href="{{ route('register.create') }}"
                    class="text-stone-600 hover:text-blue-600"
                >
                    {{ __('auth.nav_register') }}
                </a>

            @endauth

        </div>
    </div>
</nav>
