@extends('layouts.app')

@section('title', $item->translate('en')?->name)

@section('content')

    {{-- Product Details --}}

    <div class="grid gap-8 lg:grid-cols-2">

        {{-- Product Image --}}

        <div>
            @if ($item->getFirstImageUrl())

                <img
                    src="{{ $item->getFirstImageUrl() }}"
                    class="w-full rounded-xl object-cover"
                    alt="{{ $item->translate('en')?->name }}"
                >

            @else

                <div class="flex h-64 items-center justify-center rounded-xl bg-stone-100 text-stone-400">
                    No image
                </div>

            @endif
        </div>


        {{-- Product Information --}}

        <div>

            <p class="text-sm text-stone-500">
                {{ $item->category?->translate('en')?->name }}
            </p>

            <h1 class="mt-1 font-display text-3xl font-semibold text-stone-900">
                {{ $item->translate('en')?->name }}
            </h1>

            <p class="mt-2 text-xl font-medium text-stone-900">
                {{ number_format($item->price, 2) }}
            </p>

            <p class="mt-4 text-stone-600">
                {{ $item->translate('en')?->description }}
            </p>


            {{-- Cart Validation Error --}}

            @error('item_attribute_id')
            <p class="mt-4 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror


            {{-- Add To Cart --}}

            <form
                method="POST"
                action="{{ route('cart.store') }}"
                class="mt-6 space-y-3"
            >
                @csrf

                <input
                    type="hidden"
                    name="item_id"
                    value="{{ $item->id }}"
                >


                {{-- Variants --}}

                @if ($item->variants->isNotEmpty())

                    <select
                        name="item_attribute_id"
                        required
                        class="block w-full rounded-lg border border-stone-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">
                            Choose an option
                        </option>

                        @foreach ($item->variants as $variant)

                            <option
                                value="{{ $variant->id }}"
                                @disabled($variant->stock <= 0)
                            >
                                {{ $variant->name }}: {{ $variant->value }}

                                @if ($variant->price_modifier != 0)
                                    ({{ $variant->price_modifier > 0 ? '+' : '' }}{{ number_format($variant->price_modifier, 2) }})
                                @endif

                                @if ($variant->stock <= 0)
                                    — Out of stock
                                @endif
                            </option>

                        @endforeach
                    </select>

                @endif


                <input
                    type="hidden"
                    name="quantity"
                    value="1"
                >


                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 font-medium text-white hover:bg-blue-700"
                >
                    Add to cart
                </button>

            </form>


            {{-- Wishlist --}}

            @auth

                <form
                    method="POST"
                    action="{{ route('wishlist.toggle', $item) }}"
                    class="mt-2"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm font-medium text-stone-700 hover:bg-stone-50"
                    >
                        {{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}
                    </button>
                </form>

            @endauth

        </div>
    </div>


    {{-- Reviews --}}

    <div class="mt-12">

        <h2 class="text-xl font-semibold text-stone-900">
            Reviews
        </h2>


        {{-- Add Review --}}

        @if ($canReview)

            <form
                method="POST"
                action="{{ route('reviews.store', $item) }}"
                class="mt-4 max-w-md space-y-3 rounded-xl border border-stone-200 bg-white p-4"
            >
                @csrf


                {{-- Rating --}}

                <div>

                    <label
                        for="rating"
                        class="block text-sm font-medium text-stone-700"
                    >
                        Rating
                    </label>

                    <select
                        id="rating"
                        name="rating"
                        required
                        class="mt-1 rounded-lg border border-stone-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                        @for ($i = 5; $i >= 1; $i--)

                            <option value="{{ $i }}">
                                {{ $i }} star{{ $i > 1 ? 's' : '' }}
                            </option>

                        @endfor

                    </select>

                </div>


                {{-- Review Body --}}

                <div>

                    <label
                        for="body"
                        class="block text-sm font-medium text-stone-700"
                    >
                        Your review (optional)
                    </label>

                    <textarea
                        id="body"
                        name="body"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('body') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Submit review
                </button>

            </form>

        @endif


        {{-- Reviews List --}}

        <div class="mt-6 space-y-4">

            @forelse ($reviews as $review)

                <div class="rounded-xl border border-stone-200 bg-white p-4">

                    <p class="font-medium text-stone-900">
                        {{ $review->user->name }}
                        &middot;
                        {{ $review->rating }}/5
                    </p>

                    @if ($review->body)

                        <p class="mt-1 text-stone-600">
                            {{ $review->body }}
                        </p>

                    @endif

                </div>

            @empty

                <p class="text-stone-500">
                    No reviews yet.
                </p>

            @endforelse

        </div>

    </div>


    {{-- Comments --}}

    <div class="mt-12">

        <h2 class="text-xl font-semibold text-stone-900">
            Comments
        </h2>


        {{-- Add Comment --}}

        @auth

            <form
                method="POST"
                action="{{ route('comments.store', $item) }}"
                class="mt-4 max-w-lg space-y-3"
            >
                @csrf

                <textarea
                    name="body"
                    rows="3"
                    required
                    placeholder="Write a comment..."
                    class="block w-full rounded-lg border border-stone-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >{{ old('body') }}</textarea>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Post comment
                </button>

            </form>

        @endauth


        {{-- Comments List --}}

        <div class="mt-6 space-y-5">

            @forelse ($comments as $comment)

                <div class="rounded-xl border border-stone-200 bg-white p-4">

                    <p class="font-medium text-stone-900">
                        {{ $comment->user->name }}
                    </p>

                    <p class="mt-1 text-stone-600">
                        {{ $comment->body }}
                    </p>


                    {{-- Replies --}}

                    @if ($comment->replies->isNotEmpty())

                        <div class="mt-3 space-y-3 border-r-2 border-stone-100 pr-4">

                            @foreach ($comment->replies as $reply)

                                <div>

                                    <p class="text-sm font-medium text-stone-900">
                                        {{ $reply->user->name }}
                                    </p>

                                    <p class="text-sm text-stone-600">
                                        {{ $reply->body }}
                                    </p>

                                </div>

                            @endforeach

                        </div>

                    @endif


                    {{-- Reply --}}

                    @auth

                        <form
                            method="POST"
                            action="{{ route('comments.store', $item) }}"
                            class="mt-3 flex gap-2"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="parent_id"
                                value="{{ $comment->id }}"
                            >

                            <input
                                type="text"
                                name="body"
                                required
                                placeholder="Reply..."
                                class="flex-1 rounded-lg border border-stone-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >

                            <button
                                type="submit"
                                class="rounded-lg border border-stone-300 px-3 py-1.5 text-sm text-stone-700 hover:bg-stone-50"
                            >
                                Reply
                            </button>

                        </form>

                    @endauth

                </div>

            @empty

                <p class="text-stone-500">
                    No comments yet.
                </p>

            @endforelse

        </div>

    </div>

@endsection
