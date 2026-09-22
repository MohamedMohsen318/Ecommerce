@extends('layouts.admin')

@section('title', __('admin.order') . ' #' . $order->id)

@section('content')

    <div>
        <h2 class="text-xl font-semibold text-stone-900">{{ __('admin.order') }} #{{ $order->id }}</h2>
        <p class="mt-2 text-stone-500">{{ __('admin.customer') }}: {{ $order->user->name }} ({{ $order->user->email }})</p>
        <p class="text-stone-500">{{ __('admin.shipping_to') }}: {{ $order->shipping_address }}</p>
        <p class="mt-2">
            <span class="rounded-full bg-stone-100 px-3 py-1 text-sm text-stone-700">{{ $order->status->label() }}</span>
        </p>
    </div>

    @error('status') <p class="mt-4 text-sm text-red-600">{{ $message }}</p> @enderror

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <div class="space-y-3 lg:col-span-2">
            <h3 class="font-medium text-stone-900">{{ __('admin.items') }}</h3>

            @foreach ($order->items as $item)
                <div class="flex justify-between rounded-lg border border-stone-200 bg-white p-4">
                    <div>
                        <p class="font-medium text-stone-900">{{ $item->item_name }}</p>
                        @if ($item->variant_label)
                            <p class="text-sm text-stone-500">{{ $item->variant_label }}</p>
                        @endif
                        <p class="text-sm text-stone-500">Qty: {{ $item->quantity }} &times; {{ number_format($item->unit_price, 2) }}</p>
                    </div>
                    <p class="font-medium text-stone-900">{{ number_format($item->lineTotal(), 2) }}</p>
                </div>
            @endforeach

            <div class="space-y-1 pt-2 text-right">
                <p class="text-stone-500">{{ __('admin.subtotal') }}: {{ number_format($order->subtotal, 2) }}</p>

                @if ($order->discount_amount > 0)
                    <p class="text-stone-500">
                        {{ __('admin.discount') }}
                        @if ($order->discount?->code) ({{ $order->discount->code }}) @endif
                        : -{{ number_format($order->discount_amount, 2) }}
                    </p>
                @endif

                @if ($order->points_discount_amount > 0)
                    <p class="text-stone-500">
                        {{ __('admin.points_redeemed') }} ({{ $order->points_redeemed }}): -{{ number_format($order->points_discount_amount, 2) }}
                    </p>
                @endif

                <p class="text-lg font-semibold text-stone-900">{{ __('admin.total') }}: {{ number_format($order->total, 2) }}</p>
            </div>

            <h3 class="pt-6 font-medium text-stone-900">{{ __('admin.status_history') }}</h3>
            <div class="space-y-2">
                @foreach ($order->statusHistory as $history)
                    <div class="rounded-lg border border-stone-200 bg-white p-3 text-sm">
                        <span class="font-medium text-stone-900">{{ $history->status->label() }}</span>
                        <span class="text-stone-500"> &middot; {{ $history->created_at->format('M j, Y H:i') }}</span>
                        @if ($history->admin)
                            <span class="text-stone-500"> &middot; {{ __('admin.by') }} {{ $history->admin->name }}</span>
                        @endif
                        @if ($history->note)
                            <p class="mt-1 text-stone-600">{{ $history->note }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            @if ($nextStatuses->isNotEmpty())
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-3 rounded-xl border border-stone-200 bg-white p-4">
                    @csrf @method('PATCH')
                    <div>
                        <label for="status" class="block text-sm font-medium text-stone-700">{{ __('admin.change_status_to') }}</label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                            @foreach ($nextStatuses as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="note" class="block text-sm font-medium text-stone-700">{{ __('admin.note_optional') }}</label>
                        <textarea id="note" name="note" rows="2" class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                        {{ __('admin.update_status') }}
                    </button>
                </form>
            @else
                <p class="text-sm text-stone-500">{{ __('admin.no_further_changes') }}</p>
            @endif
        </div>
    </div>
@endsection
