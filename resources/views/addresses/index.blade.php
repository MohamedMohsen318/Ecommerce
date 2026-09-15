@extends('layouts.app')

@section('title', 'Your addresses')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">Your addresses</h1>

    <div class="mt-6 grid gap-8 sm:grid-cols-2">
        <div class="space-y-4">
            @forelse ($addresses as $address)
                <div class="rounded-xl border border-stone-200 bg-white p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            @if ($address->label)
                                <p class="text-sm font-medium text-stone-900">{{ $address->label }}</p>
                            @endif
                            <p class="text-sm text-stone-600">{{ $address->line }}</p>
                            @if ($address->is_default)
                                <span class="mt-1 inline-block rounded-full bg-emerald-100 px-2 py-0.5 text-xs text-emerald-700">Default</span>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('addresses.destroy', $address) }}"
                              onsubmit="return confirm('Remove this address?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Remove</button>
                        </form>
                    </div>

                    @unless ($address->is_default)
                        <form method="POST" action="{{ route('addresses.update', $address) }}" class="mt-2">
                            @csrf @method('PATCH')
                            <input type="hidden" name="line" value="{{ $address->line }}">
                            <input type="hidden" name="label" value="{{ $address->label }}">
                            <input type="hidden" name="is_default" value="1">
                            <button type="submit" class="text-xs text-brand-600 hover:underline">Make default</button>
                        </form>
                    @endunless
                </div>
            @empty
                <p class="text-stone-500">No saved addresses yet.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('addresses.store') }}" class="space-y-4 rounded-xl border border-stone-200 bg-white p-4">
            @csrf
            <h2 class="font-medium text-stone-900">Add a new address</h2>

            <div>
                <label for="label" class="block text-sm font-medium text-stone-700">Label (optional)</label>
                <input id="label" name="label" type="text" placeholder="Home, Work..."
                       class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
            </div>

            <div>
                <label for="line" class="block text-sm font-medium text-stone-700">Address</label>
                <textarea id="line" name="line" rows="3" required
                          class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
            </div>

            <label class="flex items-center gap-2 text-sm text-stone-600">
                <input type="checkbox" name="is_default" value="1" class="rounded border-stone-300 text-brand-600">
                Make this my default address
            </label>

            <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                Save address
            </button>
        </form>
    </div>
@endsection
