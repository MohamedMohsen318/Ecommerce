@extends('layouts.admin')

@section('title', __('admin.orders'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.orders') }}</h2>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">{{ __('admin.order') }}</th>
                <th class="px-4 py-3">{{ __('admin.customer') }}</th>
                <th class="px-4 py-3">{{ __('admin.total') }}</th>
                <th class="px-4 py-3">{{ __('admin.status') }}</th>
                <th class="px-4 py-3">{{ __('admin.date') }}</th>
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
                        <span class="rounded-full bg-stone-100 px-2 py-0.5 text-xs text-stone-600">{{ $order->status->label() }}</span>
                    </td>
                    <td class="px-4 py-3 text-stone-500">{{ $order->created_at->format('M j, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:underline">{{ __('admin.view') }}</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-stone-500">{{ __('admin.no_results') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
