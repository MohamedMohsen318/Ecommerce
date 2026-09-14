@extends('layouts.app')

@section('title', 'Verify your email')

@section('content')
    <div class="mx-auto max-w-md text-center">
        <h1 class="font-display text-2xl font-semibold text-stone-900">Verify your email</h1>
        <p class="mt-3 text-stone-600">
            We've sent a verification link to your email address. Click the link to activate your account.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                Resend verification email
            </button>
        </form>
    </div>
@endsection
