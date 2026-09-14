@extends('layouts.app')

@section('title', 'Forgot password')

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="font-display text-2xl font-semibold text-stone-900">Forgot your password?</h1>
        <p class="mt-2 text-sm text-stone-500">Enter your email and we'll send you a reset link.</p>

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                Send reset link
            </button>
        </form>
    </div>
@endsection
