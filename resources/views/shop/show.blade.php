@extends('layouts.app')

@section('title', $item->translate('en')?->name)

@section('content')
    <div class="grid gap-8 lg:grid-cols-2">
        <div>
            @if ($item->getFirstImageUrl())
                <img src="{{ $item->getFirstImageUrl() }}" class="w-full rounded-xl object-cover" alt="">
            @else
                <div class="flex h-64 items-center justify-center rounded-xl bg-stone-100 text-stone-400">No image</div>
            @endif
        </div>

        <div>
            <p class="text-sm text-stone-500">{{ $item->category?->translate('en')?->name }}</p>
            <h1 class="mt-1 font-display text-3xl font-semibold text-stone-900">{{ $item->translate('en')?->name }}</h1>
            <p class="mt-2 text-xl font-medium text-stone-900">{{ number_format($item->price, 2) }}</p>
            <p class="mt-4 text-stone-600">{{ $item->translate('en')?->description }}</p>

            @error('review') <p class="mt-4 text-sm text-red-600">{{ $message }}</p> @enderror

            <form method="POST" action="{{ route('cart.store') }}" class="mt-6 space-y-2">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                @if ($item->variants->isNotEmpty())
                    <select name="item_attribute_id" required
                            class="block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">Choose an option</option>
                        @foreach ($item->variants as $variant)
                            <option value="{{ $variant->id }}" @disabled($variant->stock <= 0)>
                                {{ $variant->name }}: {{ $variant->value }}
                                @if ($variant->stock <= 0) — Out of stock @endif
                            </option>
                        @endforeach
                    </select>
                @endif

                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                    Add to cart
                </button>
            </form>

            @auth
                <form method="POST" action="{{ route('wishlist.toggle', $item) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50">
                        {{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <div class="mt-12">
        <h2 class="text-xl font-semibold text-stone-900">Reviews</h2>

        @if ($canReview)
            <form method="POST" action="{{ route('reviews.store', $item) }}" class="mt-4 max-w-md space-y-3 rounded-xl border border-stone-200 bg-white p-4">
                @csrf
                <div>
                    <label for="rating" class="block text-sm font-medium text-stone-700">Rating</label>
                    <select id="rating" name="rating" required class="mt-1 rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="body" class="block text-sm font-medium text-stone-700">Your review (optional)</label>
                    <textarea id="body" name="body" rows="3" class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                </div>
                <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                    Submit review
                </button>
            </form>
        @endif

        <div class="mt-6 space-y-4">
            @forelse ($reviews as $review)
                <div class="rounded-xl border border-stone-200 bg-white p-4">
                    <p class="font-medium text-stone-900">{{ $review->user->name }} &middot; {{ $review->rating }}/5</p>
                    @if ($review->body)
                        <p class="mt-1 text-stone-600">{{ $review->body }}</p>
                    @endif
                </div>
            @empty
                <p class="text-stone-500">No reviews yet.</p>
            @endforelse
        </div>
    </div>
@endsection
