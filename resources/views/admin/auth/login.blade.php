@extends('layouts.admin-auth')

@section('title', __('auth.login_title'))

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Admin access</p>
        <h1 class="mt-2 font-display text-3xl font-bold text-slate-950">
            {{ __('auth.login_title') }} (Admin)
        </h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Sign in to manage products, orders, discounts, and store settings.
        </p>

        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">
                    {{ __('auth.email') }}
                </label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >

                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700">
                    {{ __('auth.password') }}
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >

                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-stone-600">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="rounded border-stone-300 text-blue-600 focus:ring-blue-500"
                >

                {{ __('auth.remember_me') }}
            </label>

            <button
                type="submit"
                class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                {{ __('auth.login_button') }}
            </button>
        </form>

        <p class="mt-5 text-center text-sm">
            <a
                href="{{ route('admin.password.request') }}"
                class="font-semibold text-blue-700 hover:underline"
            >
                Forgot your password?
            </a>
        </p>
    </div>
@endsection
