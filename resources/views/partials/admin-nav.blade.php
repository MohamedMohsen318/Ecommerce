<aside class="w-64 shrink-0 border-r border-stone-200 bg-stone-900 text-stone-200">
    <div class="border-b border-stone-800 px-6 py-5">
        <span class="font-display text-lg font-semibold text-white">{{ config('app.name') }}</span>
        <span class="block text-xs text-stone-400">Admin panel</span>
    </div>

    <nav class="space-y-1 px-3 py-4 text-sm">
        @if (auth('admins')->user()?->can('view_dashboard'))
            <a href="{{ route('admin.dashboard.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.dashboard.*') ? 'bg-stone-800 text-white' : '' }}">
                Dashboard
            </a>
        @endif

        @if (auth('admins')->user()?->can('manage_admins'))
            <a href="{{ route('admin.admins.index') }}"
               class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.admins.*') ? 'bg-stone-800 text-white' : '' }}">
                Admins
            </a>
        @endif
    </nav>

    @if (auth('admins')->user()?->can('manage_categories'))
        <a href="{{ route('admin.categories.index') }}"
           class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.categories.*') ? 'bg-stone-800 text-white' : '' }}">
            Categories
        </a>
    @endif

    @if (auth('admins')->user()?->can('manage_items'))
        <a href="{{ route('admin.items.index') }}"
           class="block rounded-lg px-3 py-2 hover:bg-stone-800 {{ request()->routeIs('admin.items.*') ? 'bg-stone-800 text-white' : '' }}">
            Items
        </a>
    @endif

    <form method="POST" action="{{ route('admin.logout') }}" class="border-t border-stone-800 px-3 py-4">
        @csrf
        <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-stone-800">Log out</button>
    </form>
</aside>
