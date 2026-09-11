@extends('layouts.app')

@section('title', __('auth.login_title'))

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="font-display text-3xl font-semibold text-stone-900">{{ __('auth.login_title') }}</h1>

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">{{ __('auth.email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700">{{ __('auth.password') }}</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-stone-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-stone-300 text-brand-600">
                {{ __('auth.remember_me') }}
            </label>

            <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                {{ __('auth.login_button') }}
            </button>
        </form>

        <p class="mt-6 text-sm text-stone-500">
            {{ __('auth.no_account') }}
            <a href="{{ route('register.create') }}" class="font-medium text-brand-600 hover:underline">{{ __('auth.create_account_link') }}</a>
        </p>
    </div>
@endsection
