@extends('layouts.app')

@section('title', __('auth.register_title'))

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="font-display text-3xl font-semibold text-stone-900">
            {{ __('auth.register_title') }}
        </h1>

        <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-stone-700">
                    {{ __('auth.name') }}
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-lg border border-stone-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >

                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

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
                    class="mt-1 block w-full rounded-lg border border-stone-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
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
                    class="mt-1 block w-full rounded-lg border border-stone-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >

                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-stone-700">
                    {{ __('auth.password_confirmation') }}
                </label>

                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    class="mt-1 block w-full rounded-lg border border-stone-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                {{ __('auth.register_button') }}
            </button>
        </form>

        <p class="mt-6 text-sm text-stone-500">
            {{ __('auth.have_account') }}

            <a
                href="{{ route('login.create') }}"
                class="font-medium text-blue-600 hover:underline"
            >
                {{ __('auth.login_link') }}
            </a>
        </p>
    </div>
@endsection
