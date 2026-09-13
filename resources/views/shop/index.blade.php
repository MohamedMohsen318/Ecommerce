@extends('layouts.app')

@section('title', 'Shop')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">Shop</h1>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($items as $item)
            <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
                @if ($item->getFirstImageUrl())
                    <img src="{{ $item->getFirstImageUrl() }}" class="h-40 w-full object-cover" alt="">
                @else
                    <div class="flex h-40 items-center justify-center bg-stone-100 text-stone-400">No image</div>
                @endif

                <div class="p-4">
                    <h3 class="font-medium text-stone-900">{{ $item->translate('en')?->name }}</h3>
                    <p class="mt-1 text-stone-500">{{ number_format($item->price, 2) }}</p>
                    <form method="POST" action="{{ route('cart.store') }}" class="mt-3 space-y-2">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">

                        @if ($item->variants->isNotEmpty())
                            <select name="item_attribute_id" required
                                    class="block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                                <option value="">Choose an option</option>
                                @foreach ($item->variants as $variant)
                                    <option value="{{ $variant->id }}" @disabled($variant->stock <= 0)>
                                        {{ $variant->name }}: {{ $variant->value }}
                                        @if ($variant->price_modifier != 0)
                                            ({{ $variant->price_modifier > 0 ? '+' : '' }}{{ number_format($variant->price_modifier, 2) }})
                                        @endif
                                        @if ($variant->stock <= 0) — Out of stock @endif
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full rounded-lg bg-brand-600 px-3 py-2 text-sm font-medium text-white hover:bg-brand-700">
                            Add to cart
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $items->links() }}</div>
@endsection
