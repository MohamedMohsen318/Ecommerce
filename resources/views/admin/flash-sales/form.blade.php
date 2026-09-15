@csrf
@isset($flashSale) @method('PUT') @endisset

<div class="max-w-2xl space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-stone-700">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $flashSale->name ?? '') }}" required
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="starts_at" class="block text-sm font-medium text-stone-700">Starts at</label>
            <input id="starts_at" name="starts_at" type="datetime-local" required
                   value="{{ old('starts_at', isset($flashSale) ? $flashSale->starts_at->format('Y-m-d\TH:i') : '') }}"
                   class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            @error('starts_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="ends_at" class="block text-sm font-medium text-stone-700">Ends at</label>
            <input id="ends_at" name="ends_at" type="datetime-local" required
                   value="{{ old('ends_at', isset($flashSale) ? $flashSale->ends_at->format('Y-m-d\TH:i') : '') }}"
                   class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            @error('ends_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm text-stone-600">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $flashSale->is_active ?? true))
               class="rounded border-stone-300 text-brand-600">
        Active
    </label>

    <div>
        <span class="block text-sm font-medium text-stone-700">Items on sale</span>
        @php
            $existingPrices = isset($flashSale)
                ? $flashSale->items->pluck('pivot.sale_price', 'id')
                : collect();
        @endphp
        <div class="mt-2 max-h-80 space-y-2 overflow-y-auto rounded-lg border border-stone-200 p-3">
            @foreach ($items as $item)
                @php $selected = $existingPrices->has($item->id); @endphp
                <div class="grid grid-cols-[1fr_auto_120px] items-center gap-2">
                    <label class="flex items-center gap-2 text-sm text-stone-700">
                        <input type="checkbox" onchange="document.getElementById('price-{{ $item->id }}').disabled = !this.checked"
                               @checked($selected) class="rounded border-stone-300 text-brand-600">
                        <input type="hidden" name="items[{{ $loop->index }}][item_id]" value="{{ $item->id }}">
                        {{ $item->translate('en')?->name }}
                    </label>
                    <span class="text-sm text-stone-400">was {{ number_format($item->price, 2) }}</span>
                    <input id="price-{{ $item->id }}" name="items[{{ $loop->index }}][sale_price]" type="number" step="0.01" min="0"
                           value="{{ $existingPrices->get($item->id) }}" @disabled(! $selected)
                           placeholder="Sale price"
                           class="rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
        {{ isset($flashSale) ? 'Save changes' : 'Create flash sale' }}
    </button>
</div>
