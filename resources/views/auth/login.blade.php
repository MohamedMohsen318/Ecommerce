@extends('layouts.app')

@section('title', __('auth.login_title'))

@section('content') <div class="mx-auto max-w-md">

    <h1 class="font-display text-3xl font-semibold text-stone-900">
        {{ __('auth.login_title') }}
    </h1>

    <form
        method="POST"
        action="{{ route('login.store') }}"
        class="mt-8 space-y-5"
    >
        @csrf

        {{-- Email --}}

        <div>
            <label
                for="email"
                class="block text-sm font-medium text-stone-700"
            >
                {{ __('auth.email') }}
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="mt-1 block w-full rounded-lg border border-stone-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
            >

            @error('email')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Password --}}

        <div>
            <label
                for="password"
                class="block text-sm font-medium text-stone-700"
            >
                {{ __('auth.password') }}
            </label>

            <input
                id="password"
                name="password"
                type="password"
                required
                class="mt-1 block w-full rounded-lg border border-stone-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
            >

            @error('password')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Remember Me --}}

        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input
                type="checkbox"
                name="remember"
                value="1"
                class="rounded border-stone-300 text-blue-600 focus:ring-blue-500"
            >

            {{ __('auth.remember_me') }}
        </label>

        {{-- Submit --}}

        <button
            type="submit"
            class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            {{ __('auth.login_button') }}
        </button>
    </form>

    {{-- Links --}}

    <div class="mt-6 space-y-2 text-sm">

        <p class="text-stone-500">
            {{ __('auth.no_account') }}

            <a
                href="{{ route('register.create') }}"
                class="font-medium text-blue-600 hover:underline"
            >
                {{ __('auth.create_account_link') }}
            </a>
        </p>

        <p>
            <a
                href="{{ route('password.request') }}"
                class="font-medium text-blue-600 hover:underline"
            >
                Forgot your password?
            </a>
        </p>

    </div>

</div>


@endsection
