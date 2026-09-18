@extends('layouts.app')

@section('title', $item->translate()?->name)

@section('content')

    {{-- Product Details --}}

    <div class="grid gap-8 lg:grid-cols-2">

        {{-- Product Image --}}

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">

            @if ($item->getFirstImageUrl())

                <img
                    src="{{ $item->getFirstImageUrl() }}"
                    class="aspect-square w-full rounded-xl object-cover"
                    alt="{{ $item->translate()?->name }}"
                >

            @else

                <div class="flex aspect-square items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    No image
                </div>

            @endif

        </div>


        {{-- Product Information --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">

            <p class="inline-flex rounded-full bg-teal-50 px-3 py-1 text-sm font-semibold text-teal-700">
                {{ $item->category?->translate()?->name }}
            </p>

            <h1 class="mt-4 font-display text-4xl font-bold leading-tight text-slate-950">
                {{ $item->translate()?->name }}
            </h1>

            @if ($item->effectivePrice() < $item->price)

                <p class="mt-5 flex items-baseline gap-3">

                    <span class="text-3xl font-black text-rose-600">
                        {{ number_format($item->effectivePrice(), 2) }}
                    </span>

                    <span class="text-base text-slate-400 line-through">
                        {{ number_format($item->price, 2) }}
                    </span>

                </p>

            @else

                <p class="mt-5 text-3xl font-black text-slate-950">
                    {{ number_format($item->price, 2) }}
                </p>

            @endif

            <p class="mt-5 leading-8 text-slate-600">
                {{ $item->translate()?->description }}
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
                class="mt-8 space-y-4 rounded-xl border border-slate-200 bg-slate-50 p-4"
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
                        class="block h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:border-teal-500 focus:ring-teal-500"
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
                                - Out of stock
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
                    class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"
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
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700"
                    >
                        {{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}
                    </button>

                </form>

            @endauth

        </div>

    </div>


    {{-- Reviews --}}

    <div class="mt-12 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-2xl font-bold text-slate-950">
            Reviews
        </h2>


        {{-- Add Review --}}

        @if ($canReview)

            <form
                method="POST"
                action="{{ route('reviews.store', $item) }}"
                class="mt-4 max-w-xl space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4"
            >

                @csrf


                {{-- Rating --}}

                <div>

                    <label
                        for="rating"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Rating
                    </label>

                    <select
                        id="rating"
                        name="rating"
                        required
                        class="mt-1 h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm focus:border-teal-500 focus:ring-teal-500"
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
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Your review (optional)
                    </label>

                    <textarea
                        id="body"
                        name="body"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border border-slate-200 bg-white text-sm focus:border-teal-500 focus:ring-teal-500"
                    >{{ old('body') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"
                >
                    Submit review
                </button>

            </form>

        @endif


        {{-- Reviews List --}}

        <div class="mt-6 space-y-4">

            @forelse ($reviews as $review)

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <p class="font-bold text-slate-950">

                        {{ $review->user->name }}

                        &middot;

                        {{ $review->rating }}/5

                    </p>

                    @if ($review->body)

                        <p class="mt-1 leading-7 text-slate-600">
                            {{ $review->body }}
                        </p>

                    @endif

                </div>

            @empty

                <p class="text-slate-500">
                    No reviews yet.
                </p>

            @endforelse

        </div>

    </div>


    {{-- Comments --}}

    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-2xl font-bold text-slate-950">
            Comments
        </h2>


        {{-- Add Comment --}}

        @auth

            <form
                method="POST"
                action="{{ route('comments.store', $item) }}"
                class="mt-4 max-w-2xl space-y-3"
            >

                @csrf

                <textarea
                    name="body"
                    rows="3"
                    required
                placeholder="Write a comment..."
                class="block w-full rounded-lg border border-slate-200 bg-slate-50 text-sm focus:border-teal-500 focus:ring-teal-500"
                >{{ old('body') }}</textarea>

                <button
                type="submit"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"
                >
                    Post comment
                </button>

            </form>

        @endauth


        {{-- Comments List --}}

        <div class="mt-6 space-y-5">

            @forelse ($comments as $comment)

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <p class="font-bold text-slate-950">
                        {{ $comment->user->name }}
                    </p>


                    @if (auth()->id() === $comment->user_id)

                        {{-- Edit Comment --}}

                        <form
                            method="POST"
                            action="{{ route('comments.update', $comment) }}"
                            class="mt-1"
                        >

                            @csrf
                            @method('PUT')

                            <textarea
                                name="body"
                                rows="2"
                                required
                                class="w-full rounded-lg border border-slate-200 bg-white text-sm focus:border-teal-500 focus:ring-teal-500"
                            >{{ $comment->body }}</textarea>

                            <div class="mt-1 flex gap-3">

                                <button
                                    type="submit"
                                    class="text-xs font-semibold text-teal-700 hover:underline"
                                >
                                    Save
                                </button>

                            </div>

                        </form>


                        {{-- Delete Comment --}}

                        <form
                            method="POST"
                            action="{{ route('comments.destroy', $comment) }}"
                            class="mt-1"
                            onsubmit="return confirm('Delete this comment?')"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="text-xs text-red-600 hover:underline"
                            >
                                Delete
                            </button>

                        </form>

                    @else

                        <p class="mt-1 leading-7 text-slate-600">
                            {{ $comment->body }}
                        </p>

                    @endif


                    {{-- Replies --}}

                    @if ($comment->replies->isNotEmpty())

                        <div class="mt-4 space-y-3 border-r-2 border-slate-200 pr-4">

                            @foreach ($comment->replies as $reply)

                                <div>

                                    <p class="text-sm font-bold text-slate-950">
                                        {{ $reply->user->name }}
                                    </p>

                                    @if (auth()->id() === $reply->user_id)

                                        <form
                                            method="POST"
                                            action="{{ route('comments.update', $reply) }}"
                                            class="mt-1"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <textarea
                                                name="body"
                                                rows="2"
                                                required
                                                class="w-full rounded-lg border border-slate-200 bg-white text-sm focus:border-teal-500 focus:ring-teal-500"
                                            >{{ $reply->body }}</textarea>

                                            <div class="mt-1 flex gap-3">

                                                <button
                                                    type="submit"
                                                    class="text-xs font-semibold text-teal-700 hover:underline"
                                                >
                                                    Save
                                                </button>

                                            </div>

                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('comments.destroy', $reply) }}"
                                            class="mt-1"
                                            onsubmit="return confirm('Delete this reply?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-xs text-red-600 hover:underline"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    @else

                                        <p class="text-sm leading-6 text-slate-600">
                                            {{ $reply->body }}
                                        </p>

                                    @endif

                                </div>

                            @endforeach

                        </div>

                    @endif


                    {{-- Reply --}}

                    @auth

                        <form
                            method="POST"
                            action="{{ route('comments.store', $item) }}"
                            class="mt-4 flex flex-col gap-2 sm:flex-row"
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
                                class="flex-1 rounded-lg border border-slate-200 bg-white text-sm focus:border-teal-500 focus:ring-teal-500"
                            >

                            <button
                                type="submit"
                                class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                            >
                                Reply
                            </button>

                        </form>

                    @endauth

                </div>

            @empty

                <p class="text-slate-500">
                    No comments yet.
                </p>

            @endforelse

        </div>

    </div>

@endsection
