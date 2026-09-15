
@extends('layouts.app')

@section('title', 'Shop')

@section('content')

    <h1 class="font-display text-3xl font-semibold text-stone-900">
        Shop
    </h1>


    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($items as $item)

            <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">

                {{-- Image --}}

                <a href="{{ route('shop.show', $item) }}">

                    @if ($item->getFirstImageUrl())

                        <img
                            src="{{ $item->getFirstImageUrl() }}"
                            class="h-40 w-full object-cover"
                            alt="{{ $item->translate()?->name }}"
                        >

                    @else

                        <div class="flex h-40 items-center justify-center bg-stone-100 text-stone-400">
                            No image
                        </div>

                    @endif

                </a>


                <div class="p-4">

                    {{-- Item Name --}}

                    <h3 class="font-medium text-stone-900">

                        <a
                            href="{{ route('shop.show', $item) }}"
                            class="hover:text-blue-600"
                        >
                            {{ $item->translate()?->name }}
                        </a>

                    </h3>


                    {{-- Price --}}

                    <p class="mt-1 text-stone-500">
                        {{ number_format($item->price, 2) }}
                    </p>


                    {{-- Add To Cart --}}

                    <form
                        method="POST"
                        action="{{ route('cart.store') }}"
                        class="mt-3 space-y-2"
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
                            class="w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            Add to cart
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="col-span-full py-12 text-center text-stone-500">
                No items found.
            </div>

        @endforelse

    </div>


    {{-- Pagination --}}

    <div class="mt-6">
        {{ $items->links() }}
    </div>

@endsection

