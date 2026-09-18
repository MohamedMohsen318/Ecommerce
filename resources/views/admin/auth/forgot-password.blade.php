@extends('layouts.admin-auth')

@section('title', 'Forgot password')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Admin recovery</p>
        <h1 class="mt-2 font-display text-2xl font-bold text-slate-950">Forgot your password? (Admin)</h1>
        <p class="mt-2 text-sm text-slate-500">Enter your email and we'll send you a reset link.</p>

        <form method="POST" action="{{ route('admin.password.email') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-blue-500 focus:ring-blue-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Send reset link
            </button>
        </form>
    </div>
@endsection
