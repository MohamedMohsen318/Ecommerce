@extends('layouts.app')

@section('title', 'Deals')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">Today's deals</h1>

    @forelse ($flashSales as $sale)
        <div class="mt-8">
            <div class="flex items-baseline justify-between">
                <h2 class="text-xl font-semibold text-stone-900">{{ $sale->name }}</h2>
                <p class="text-sm text-stone-500">Ends {{ $sale->ends_at->format('M j, g:ia') }}</p>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-6 sm:grid-cols-4">
                @foreach ($sale->items as $item)
                    <a href="{{ route('shop.show', $item) }}" class="group">
                        <div class="aspect-square overflow-hidden rounded-xl bg-stone-100">
                            @if ($item->getFirstImageUrl())
                                <img src="{{ $item->getFirstImageUrl() }}" class="h-full w-full object-cover" alt="">
                            @endif
                        </div>
                        <p class="mt-2 text-sm font-medium text-stone-900">{{ $item->translate()?->name }}</p>
                        <p class="text-sm">
                            <span class="text-red-600">{{ number_format($item->pivot->sale_price, 2) }}</span>
                            <span class="ml-1 text-stone-400 line-through">{{ number_format($item->price, 2) }}</span>
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @empty
        <p class="mt-6 text-stone-500">No deals running right now — check back soon.</p>
    @endforelse
@endsection
