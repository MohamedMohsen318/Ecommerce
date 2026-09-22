<aside class="flex w-64 shrink-0 flex-col border-r border-stone-200 bg-stone-900 text-stone-200">

    <div class="border-b border-stone-800 px-6 py-5">
        <span class="font-display text-lg font-semibold text-white">{{ config('app.name') }}</span>
        <span class="block text-xs text-stone-400">{{ __('admin.admin_panel') }}</span>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4 text-sm">
        @if (auth('admins')->user()?->can('view_dashboard'))
            <a href="{{ route('admin.dashboard.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.dashboard.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.dashboard') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_admins'))
            <a href="{{ route('admin.admins.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.admins.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.admins') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_categories'))
            <a href="{{ route('admin.categories.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.categories.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.categories') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_items'))
            <a href="{{ route('admin.items.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.items.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.items') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_discounts'))
            <a href="{{ route('admin.discounts.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.discounts.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.discounts') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_comments'))
            <a href="{{ route('admin.comments.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.comments.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.comments') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_flash_sales'))
            <a href="{{ route('admin.flash-sales.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.flash-sales.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.flash_sales') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_orders'))
            <a href="{{ route('admin.orders.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.orders.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.orders') }}
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_reviews'))
            <a href="{{ route('admin.reviews.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.reviews.*') ? 'bg-stone-800 text-white' : '' }}">
                {{ __('admin.reviews') }}
            </a>
        @endif
    </nav>

    <div class="border-t border-stone-800 px-3 py-4">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-stone-800">
                {{ __('admin.logout') }}
            </button>
        </form>
    </div>

</aside>
