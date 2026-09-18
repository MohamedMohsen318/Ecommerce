<nav class="sticky top-0 z-40 border-b border-white/70 bg-white/85 px-4 py-3 shadow-sm backdrop-blur sm:px-6 lg:px-8">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <a
            href="{{ route('home') }}"
            class="inline-flex items-center gap-3 font-display text-xl font-bold text-slate-950"
        >
            <span class="grid h-10 w-10 place-items-center rounded-lg bg-slate-950 text-sm font-black text-white shadow-sm">
                {{ mb_substr(config('app.name'), 0, 1) }}
            </span>
            <span>{{ config('app.name') }}</span>
        </a>

        <div class="flex flex-wrap items-center gap-2 text-sm">
            <form
                method="POST"
                action="{{ route('locale.update') }}"
            >
                @csrf

                <select
                    name="locale"
                    onchange="this.form.submit()"
                    class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                    aria-label="Language"
                >
                    @foreach (config('app.supported_locales') as $locale => $language)
                        <option
                            value="{{ $locale }}"
                            @selected(app()->getLocale() === $locale)
                        >
                            {{ $language['native'] }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a
                href="{{ route('shop.index') }}"
                class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950"
            >
                Shop
            </a>
            <a href="{{ route('deals.index') }}" class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">Deals</a>

            <a
                href="{{ route('cart.index') }}"
                class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950"
            >
                Cart
            </a>

            @auth

                <a href="{{ route('profile.edit') }}" class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">Profile</a>

                <a
                    href="{{ route('wishlist.index') }}"
                    class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950"
                >
                    Wishlist
                </a>

                <a
                    href="{{ route('addresses.index') }}"
                    class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950"
                >
                    Addresses
                </a>

                @php
                    $unreadCount = auth()->user()
                        ->unreadNotifications()
                        ->count();
                @endphp

                <a
                    href="{{ route('notifications.index') }}"
                    class="relative rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950"
                >
                    Notifications

                    @if ($unreadCount > 0)
                        <span
                            class="absolute -right-1 -top-1 rounded-full bg-rose-600 px-1.5 text-xs text-white"
                        >
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <span class="rounded-lg bg-slate-100 px-3 py-2 font-medium text-slate-700">
                    أهلاً، {{ auth()->user()->name }}
                </span>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 font-medium text-slate-600 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700"
                    >
                        تسجيل خروج
                    </button>
                </form>

            @else
                <a
                    href="{{ route('login.create') }}"
                    class="rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950"
                >
                    {{ __('auth.nav_login') }}
                </a>

                <a
                    href="{{ route('register.create') }}"
                    class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"
                >
                    {{ __('auth.nav_register') }}
                </a>

            @endauth

        </div>
    </div>
</nav>
