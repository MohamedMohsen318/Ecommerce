@csrf
@isset($discount) @method('PUT') @endisset

<div class="max-w-lg space-y-5">
    <div>
        <label for="code" class="block text-sm font-medium text-stone-700">Code</label>
        <input id="code" name="code" type="text" value="{{ old('code', $discount->code ?? '') }}" required
               class="mt-1 block w-full rounded-lg border-stone-300 uppercase focus:border-brand-500 focus:ring-brand-500">
        @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="type" class="block text-sm font-medium text-stone-700">Type</label>
        <select id="type" name="type" required
                class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            @foreach (\App\Enums\DiscountType::cases() as $type)
                <option value="{{ $type->value }}" @selected(old('type', $discount->type->value ?? null) === $type->value)>
                    {{ ucfirst($type->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="value" class="block text-sm font-medium text-stone-700">
            Value <span class="text-stone-400">(amount if fixed, percent if percentage)</span>
        </label>
        <input id="value" name="value" type="number" step="0.01" min="0"
               value="{{ old('value', $discount->value ?? '') }}" required
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
        @error('value') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="max_uses" class="block text-sm font-medium text-stone-700">Max uses <span class="text-stone-400">(optional)</span></label>
        <input id="max_uses" name="max_uses" type="number" min="1"
               value="{{ old('max_uses', $discount->max_uses ?? '') }}"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label for="expires_at" class="block text-sm font-medium text-stone-700">Expires at <span class="text-stone-400">(optional)</span></label>
        <input id="expires_at" name="expires_at" type="datetime-local"
               value="{{ old('expires_at', isset($discount) ? $discount->expires_at?->format('Y-m-d\TH:i') : '') }}"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
    </div>

    <label class="flex items-center gap-2 text-sm text-stone-600">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $discount->is_active ?? true))
               class="rounded border-stone-300 text-brand-600">
        Active
    </label>

    <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
        {{ isset($discount) ? 'Save changes' : 'Create discount' }}
    </button>
</div>
