@extends('layouts.app')

@section('title', 'Shop')

@section('content')

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-teal-700">Shop</p>
            <h1 class="mt-2 font-display text-4xl font-bold text-slate-950">
                Discover products
            </h1>
            <p class="mt-2 max-w-2xl text-slate-500">
                Browse available items, compare prices, and add your favorites to the cart.
            </p>
        </div>

        <a
            href="{{ route('cart.index') }}"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"
        >
            View cart
        </a>
    </div>


    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($items as $item)

            @php
                $hasAttributes = $item->variants->isNotEmpty();
            @endphp

            <article class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                <a href="{{ route('shop.show', $item) }}" class="relative block overflow-hidden bg-slate-100">

                    @if ($item->getFirstImageUrl())

                        <img
                            src="{{ $item->getFirstImageUrl() }}"
                            class="h-52 w-full object-cover transition duration-300 group-hover:scale-105"
                            alt="{{ $item->translate()?->name }}"
                        >

                    @else

                        <div class="flex h-52 items-center justify-center bg-slate-100 text-slate-400">
                            No image
                        </div>

                    @endif

                    @if ($item->effectivePrice() < $item->price)
                        <span class="absolute left-3 top-3 rounded-full bg-rose-600 px-3 py-1 text-xs font-bold text-white shadow">
                            Sale
                        </span>
                    @endif

                </a>


                <div class="p-5">

                    <h3 class="line-clamp-2 min-h-[3.5rem] text-lg font-bold leading-7 text-slate-950">

                        <a
                            href="{{ route('shop.show', $item) }}"
                            class="transition hover:text-teal-700"
                        >
                            {{ $item->translate()?->name }}
                        </a>

                    </h3>


                    @if ($item->effectivePrice() < $item->price)
                        <p class="mt-3 flex items-baseline gap-2">
                            <span class="text-2xl font-black text-rose-600">{{ number_format($item->effectivePrice(), 2) }}</span>
                            <span class="text-sm text-slate-400 line-through">{{ number_format($item->price, 2) }}</span>
                        </p>
                    @else
                        <p class="mt-3 text-2xl font-black text-slate-950">{{ number_format($item->price, 2) }}</p>
                    @endif

                    @if ($hasAttributes)

                        <a
                            href="{{ route('shop.show', $item) }}"
                            class="mt-3 block w-full rounded-lg bg-blue-600 px-3 py-3 text-center text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"
                        >
                            Choose options
                        </a>

                    @else

                        <form
                            method="POST"
                            action="{{ route('cart.store') }}"
                            class="mt-3"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="item_id"
                                value="{{ $item->id }}"
                            >

                            <input
                                type="hidden"
                                name="quantity"
                                value="1"
                            >

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-blue-600 px-3 py-3 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"
                            >
                                Add to cart
                            </button>

                        </form>

                    @endif

                </div>

            </article>

        @empty

            <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white py-16 text-center shadow-sm">
                <p class="text-lg font-semibold text-slate-800">No items found.</p>
                <p class="mt-1 text-sm text-slate-500">Products will appear here once they are available.</p>
            </div>

        @endforelse

    </div>


    <div class="mt-6">
        {{ $items->links() }}
    </div>

@endsection
