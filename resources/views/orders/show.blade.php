@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')

    {{-- Order Header --}}

    <div>
        <h1 class="font-display text-3xl font-semibold text-stone-900">
            Order #{{ $order->id }}
        </h1>

        <p class="mt-2 text-stone-500">
            Status:
            {{ $order->status?->label() ?? $order->status }}
        </p>
        @if ($canCancel)
            <form method="POST" action="{{ route('orders.cancel', $order) }}" class="mt-3" onsubmit="return confirm('Cancel this order?')">
                @csrf @method('PATCH')
                <button type="submit" class="text-sm text-red-600 hover:underline">Cancel order</button>
            </form>
        @endif

        <p class="mt-1 text-stone-500">
            Shipping to:
            {{ $order->shipping_address }}
        </p>
    </div>


    {{-- Order Items --}}

    <div class="mt-8 space-y-3">

        @forelse ($order->items as $item)

            <div class="flex justify-between rounded-lg border border-stone-200 bg-white p-4">

                <div>

                    <p class="font-medium text-stone-900">
                        {{ $item->item_name }}
                    </p>

                    <p class="text-sm text-stone-500">
                        Qty: {{ $item->quantity }}
                        &times;
                        {{ number_format($item->unit_price, 2) }}
                    </p>

                </div>

                <p class="font-medium text-stone-900">
                    {{ number_format($item->lineTotal(), 2) }}
                </p>

            </div>

        @empty

            <p class="text-stone-500">
                No items found for this order.
            </p>

        @endforelse

    </div>


    {{-- Order Summary --}}

    <div class="mt-6 space-y-2">

        {{-- Discount --}}

        @if ($order->discount_amount > 0)

            <div class="flex justify-end text-stone-500">
                Discount

                @if ($order->discount?->code)
                    ({{ $order->discount->code }})
                @endif

                :
                -{{ number_format($order->discount_amount, 2) }}
            </div>

        @endif


        {{-- Total --}}

        <div class="flex justify-end text-lg font-semibold text-stone-900">
            Total:
            {{ number_format($order->total, 2) }}
        </div>

    </div>

@endsection
