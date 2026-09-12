@csrf
@isset($category) @method('PUT') @endisset

<div class="max-w-lg space-y-5">
    <div>
        <label for="parent_id" class="block text-sm font-medium text-stone-700">Parent category</label>
        <select id="parent_id" name="parent_id"
                class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            <option value="">— None (top level) —</option>
            @foreach ($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? null) == $parent->id)>
                    {{ $parent->translate('en')?->name }}
                    @if ($parent->parent) ({{ $parent->parent->translate('en')?->name }}) @endif
                </option>
            @endforeach
        </select>
        @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    @php $existing = isset($category) ? $category->translate('en') : null; @endphp
    <div>
        <label for="name_en" class="block text-sm font-medium text-stone-700">Name (English)</label>
        <input id="name_en" name="translations[en][name]" type="text"
               value="{{ old('translations.en.name', $existing?->name) }}" required
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
        @error('translations.en.name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description_en" class="block text-sm font-medium text-stone-700">Description (English)</label>
        <textarea id="description_en" name="translations[en][description]" rows="3"
                  class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">{{ old('translations.en.description', $existing?->description) }}</textarea>
    </div>

    @php $existingAr = isset($category) ? $category->translate('ar') : null; @endphp
    <div>
        <label for="name_ar" class="block text-sm font-medium text-stone-700">Name (Arabic, optional)</label>
        <input id="name_ar" name="translations[ar][name]" type="text" dir="rtl"
               value="{{ old('translations.ar.name', $existingAr?->name) }}"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label for="order" class="block text-sm font-medium text-stone-700">Display order</label>
        <input id="order" name="order" type="number" min="0"
               value="{{ old('order', $category->order ?? 0) }}"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-stone-700">Image</label>
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        @if (isset($category) && $category->getFirstImageUrl())
            <img src="{{ $category->getFirstImageUrl() }}" class="mt-2 h-16 w-16 rounded-lg object-cover" alt="">
        @endif
    </div>

    <label class="flex items-center gap-2 text-sm text-stone-600">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $category->is_active ?? true))
               class="rounded border-stone-300 text-brand-600">
        Active
    </label>

    <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
        {{ isset($category) ? 'Save changes' : 'Create category' }}
    </button>
</div>
