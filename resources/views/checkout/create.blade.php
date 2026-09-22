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

            <div class="space-y-3">
                <label class="block text-sm font-medium text-stone-700">Shipping address</label>

                @foreach ($addresses as $address)
                    <label class="flex items-start gap-2 rounded-lg border border-stone-200 p-3 text-sm">
                        <input type="radio" name="address_choice" value="{{ $address->id }}"
                               @checked(old('address_choice', $address->is_default ? $address->id : null) == $address->id)
                               onchange="document.getElementById('new-address-box').classList.add('hidden')"
                               class="mt-0.5 text-blue-600 focus:ring-blue-500">
                        <span>
                            @if ($address->label) <span class="font-medium text-stone-900">{{ $address->label }}:</span> @endif
                            {{ $address->line }}
                        </span>
                    </label>
                @endforeach

                <label class="flex items-start gap-2 rounded-lg border border-stone-200 p-3 text-sm">
                    <input type="radio" name="address_choice" value="new"
                           @checked(old('address_choice', $addresses->isEmpty() ? 'new' : null) === 'new')
                           onchange="document.getElementById('new-address-box').classList.remove('hidden')"
                           class="mt-0.5 text-blue-600 focus:ring-blue-500">
                    <span>Use a new address</span>
                </label>

                <div id="new-address-box" class="{{ old('address_choice', $addresses->isEmpty() ? 'new' : null) === 'new' ? '' : 'hidden' }} space-y-2 pl-7">
                    <textarea name="shipping_address" rows="3" placeholder="Full shipping address"
                              class="block w-full rounded-lg border-stone-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('shipping_address') }}</textarea>

                    <label class="flex items-center gap-2 text-sm text-stone-600">
                        <input type="checkbox" name="save_address" value="1" class="rounded border-stone-300 text-blue-600">
                        Save this address for next time
                    </label>
                </div>

                @error('shipping_address')
                <p class="text-sm text-red-600">{{ $message }}</p>
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


            {{-- Redeem Points --}}

            @if ($pointsBalance > 0)

                <div>

                    <label
                        for="redeem_points"
                        class="block text-sm font-medium text-stone-700"
                    >
                        Redeem points

                        <span class="text-stone-400">
                             (you have {{ $pointsBalance }} — {{ config('loyalty.points_per_dollar_redeemed') }} points = $1)
                        </span>
                    </label>

                    <input
                        id="redeem_points"
                        name="redeem_points"
                        type="number"
                        min="0"
                        max="{{ $pointsBalance }}"
                        value="{{ old('redeem_points', 0) }}"
                        class="mt-1 block w-full rounded-lg border border-stone-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('redeem_points')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror

                </div>

            @endif


            {{-- Place Order --}}

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Place order
            </button>

        </form>

    </div>

@endsection
