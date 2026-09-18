@extends('layouts.admin-auth')

@section('title', 'Reset password')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Admin recovery</p>
        <h1 class="mt-2 font-display text-2xl font-bold text-slate-950">Reset your password (Admin)</h1>

        <form method="POST" action="{{ route('admin.password.update') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-blue-500 focus:ring-blue-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700">New password</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-blue-500 focus:ring-blue-500">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-stone-700">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Reset password
            </button>
        </form>
    </div>
@endsection
