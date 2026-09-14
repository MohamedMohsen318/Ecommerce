@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Orders</h2>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">Order</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($orders as $order)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">#{{ $order->id }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $order->user->name }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ number_format($order->total, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full bg-stone-100 px-2 py-0.5 text-xs text-stone-600">
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-stone-500">{{ $order->created_at->format('M j, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-stone-500">No orders yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
