@extends('layouts.app')

@section('title', 'Your profile')

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="font-display text-3xl font-semibold text-stone-900">Your profile</h1>

        <form method="POST" action="{{ route('profile.update') }}" class="mt-8 space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block text-sm font-medium text-stone-700">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-stone-700">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <p class="text-sm text-stone-500">
                Manage your saved addresses in your <a href="{{ route('addresses.index') }}" class="text-brand-600 hover:underline">address book</a>.
            </p>

            <div class="border-t border-stone-200 pt-5">
                <label for="password" class="block text-sm font-medium text-stone-700">
                    New password <span class="text-stone-400">(leave blank to keep current)</span>
                </label>
                <input id="password" name="password" type="password"
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-stone-700">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            </div>

            <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                Save changes
            </button>
        </form>
    </div>
@endsection
