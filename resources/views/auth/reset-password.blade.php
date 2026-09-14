@extends('layouts.app')

@section('title', 'Reset password')

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="font-display text-2xl font-semibold text-stone-900">Reset your password</h1>

        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700">New password</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-stone-700">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            </div>

            <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                Reset password
            </button>
        </form>
    </div>
@endsection
