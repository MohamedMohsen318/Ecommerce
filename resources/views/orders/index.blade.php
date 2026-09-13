@extends('layouts.app')

@section('title', 'My orders')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">My orders</h1>

    <div class="mt-8 space-y-4">
        @forelse ($orders as $order)
            <a href="{{ route('orders.show', $order) }}"
               class="flex items-center justify-between rounded-xl border border-stone-200 bg-white p-4 hover:border-brand-500">
                <div>
                    <p class="font-medium text-stone-900">Order #{{ $order->id }}</p>
                    <p class="text-sm text-stone-500">{{ $order->created_at->format('M j, Y') }} &middot; {{ $order->status->label() }}</p>
                </div>
                <p class="font-medium text-stone-900">{{ number_format($order->total, 2) }}</p>
            </a>
        @empty
            <p class="text-stone-500">No orders yet.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
