@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

    <h1 class="font-display text-3xl font-semibold text-stone-900">
        Checkout
    </h1>


    {{-- Checkout Error --}}

    @error('checkout')
    <p class="mt-4 text-sm text-red-600">
        {{ $message }}
    </p>
    @enderror


    <div class="mt-8 grid gap-8 lg:grid-cols-3">

        {{-- Order Summary --}}

        <div class="space-y-3 lg:col-span-2">

            @foreach ($cart->items as $cartItem)

                <div class="flex justify-between rounded-lg border border-stone-200 bg-white p-4">

                    <div>

                        <p class="font-medium text-stone-900">
                            {{ $cartItem->item?->translate()?->name }}
                        </p>

                        @if ($cartItem->variant)

                            <p class="text-sm text-stone-500">
                                {{ $cartItem->variant->name }}:
                                {{ $cartItem->variant->value }}
                            </p>

                        @endif

                        <p class="text-sm text-stone-500">
                            Qty: {{ $cartItem->quantity }}
                        </p>

                    </div>

                    <p class="font-medium text-stone-900">
                        {{ number_format($cartItem->lineTotal(), 2) }}
                    </p>

                </div>

            @endforeach


            {{-- Subtotal --}}

            <div class="flex justify-end pt-2 text-lg font-semibold text-stone-900">
                Subtotal:
                {{ number_format($cart->subtotal(), 2) }}
            </div>

        </div>


        {{-- Checkout Form --}}

        <form
            method="POST"
            action="{{ route('checkout.store') }}"
            class="space-y-4 rounded-xl border border-stone-200 bg-white p-5"
        >

            @csrf


            {{-- Shipping Address --}}

            <div>

                <label
                    for="shipping_address"
                    class="block text-sm font-medium text-stone-700"
                >
                    Shipping address
                </label>

                <textarea
                    id="shipping_address"
                    name="shipping_address"
                    rows="4"
                    required
                    class="mt-1 block w-full rounded-lg border border-stone-300 focus:border-blue-500 focus:ring-blue-500"
                >{{ old('shipping_address') }}</textarea>

                @error('shipping_address')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Discount Code --}}

            <div>

                <label
                    for="discount_code"
                    class="block text-sm font-medium text-stone-700"
                >
                    Discount code
                    <span class="text-stone-400">(optional)</span>
                </label>

                <input
                    id="discount_code"
                    name="discount_code"
                    type="text"
                    value="{{ old('discount_code') }}"
                    class="mt-1 block w-full rounded-lg border border-stone-300 uppercase focus:border-blue-500 focus:ring-blue-500"
                >

                @error('discount_code')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Place Order --}}

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-600 px-4 py-2.5 font-medium text-white hover:bg-blue-700"
            >
                Place order
            </button>

        </form>

    </div>

@endsection
