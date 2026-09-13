@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">Your cart</h1>

    <div class="mt-8 space-y-4">
        @forelse ($cart->items as $cartItem)
            <div class="flex items-center justify-between rounded-xl border border-stone-200 bg-white p-4">
                <div>
                    <p class="font-medium text-stone-900">{{ $cartItem->item?->translate('en')?->name ?? 'Unavailable' }}</p>
                    @if ($cartItem->variant)
                        <p class="text-sm text-stone-500">{{ $cartItem->variant->name }}: {{ $cartItem->variant->value }}</p>
                    @endif
                    <p class="text-sm text-stone-500">{{ number_format($cartItem->unitPrice(), 2) }} each</p>
                </div>

                <div class="flex items-center gap-4">
                    <form method="POST" action="{{ route('cart.update', $cartItem) }}">
                        @csrf @method('PUT')
                        <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1"
                               onchange="this.form.submit()"
                               class="w-16 rounded-lg border-stone-300 text-center focus:border-brand-500 focus:ring-brand-500">
                    </form>

                    <p class="w-20 text-right font-medium text-stone-900">{{ number_format($cartItem->lineTotal(), 2) }}</p>

                    <form method="POST" action="{{ route('cart.destroy', $cartItem) }}"
                          onsubmit="return confirm('Remove this item?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Remove</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-stone-500">Your cart is empty.</p>
        @endforelse
    </div>

    @if ($cart->items->isNotEmpty())
        <div class="mt-6 flex justify-end text-lg font-semibold text-stone-900">
            Subtotal: {{ number_format($cart->subtotal(), 2) }}
        </div>
    @endif
@endsection
