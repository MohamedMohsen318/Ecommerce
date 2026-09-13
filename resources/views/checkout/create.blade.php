@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">Checkout</h1>

    @error('checkout') <p class="mt-4 text-sm text-red-600">{{ $message }}</p> @enderror

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-3">
            @foreach ($cart->items as $cartItem)
                <div class="flex justify-between rounded-lg border border-stone-200 bg-white p-4">
                    <div>
                        <p class="font-medium text-stone-900">{{ $cartItem->item?->translate('en')?->name }}</p>
                        <p class="text-sm text-stone-500">Qty: {{ $cartItem->quantity }}</p>
                    </div>
                    <p class="font-medium text-stone-900">{{ number_format($cartItem->lineTotal(), 2) }}</p>
                </div>
            @endforeach

            <div class="flex justify-end pt-2 text-lg font-semibold text-stone-900">
                Subtotal: {{ number_format($cart->subtotal(), 2) }}
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="shipping_address" class="block text-sm font-medium text-stone-700">Shipping address</label>
                <textarea id="shipping_address" name="shipping_address" rows="4" required
                          class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">{{ old('shipping_address') }}</textarea>
                @error('shipping_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                Place order
            </button>
        </form>
    </div>
@endsection
