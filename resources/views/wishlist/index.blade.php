@extends('layouts.app')

@section('title', 'My wishlist')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">My wishlist</h1>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($items as $item)
            <a href="{{ route('shop.show', $item) }}" class="overflow-hidden rounded-xl border border-stone-200 bg-white">
                @if ($item->getFirstImageUrl())
                    <img src="{{ $item->getFirstImageUrl() }}" class="h-40 w-full object-cover" alt="">
                @endif
                <div class="p-4">
                    <h3 class="font-medium text-stone-900">{{ $item->translate('en')?->name }}</h3>
                    <p class="mt-1 text-stone-500">{{ number_format($item->price, 2) }}</p>
                </div>
            </a>
        @empty
            <p class="text-stone-500">Your wishlist is empty.</p>
        @endforelse
    </div>
@endsection
