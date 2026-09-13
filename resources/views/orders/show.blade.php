@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">Order #{{ $order->id }}</h1>
    <p class="mt-1 text-stone-500">Status: {{ $order->status->label() }}</p>
    <p class="mt-1 text-stone-500">Shipping to: {{ $order->shipping_address }}</p>

    <div class="mt-8 space-y-3">
        @foreach ($order->items as $item)
            <div class="flex justify-between rounded-lg border border-stone-200 bg-white p-4">
                <div>
                    <p class="font-medium text-stone-900">{{ $item->item_name }}</p>
                    <p class="text-sm text-stone-500">Qty: {{ $item->quantity }} &times; {{ number_format($item->unit_price, 2) }}</p>
                </div>
                <p class="font-medium text-stone-900">{{ number_format($item->lineTotal(), 2) }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 flex justify-end text-lg font-semibold text-stone-900">
        Total: {{ number_format($order->total, 2) }}
    </div>
@endsection
