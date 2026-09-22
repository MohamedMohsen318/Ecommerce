@csrf
@isset($item) @method('PUT') @endisset

<div class="max-w-lg space-y-5">

    <div>
        <label for="category_id" class="block text-sm font-medium text-stone-700">
            {{ __('admin.category') }}
        </label>

        <select id="category_id"
                name="category_id"
                class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            <option value="">{{ __('admin.uncategorized') }}</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $item->category_id ?? null) == $category->id)>
                    {{ $category->translate()?->name }}
                </option>
            @endforeach
        </select>

        @error('category_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @php
        $existing = isset($item) ? $item->translate() : null;
    @endphp

    <div>
        <label for="name_en" class="block text-sm font-medium text-stone-700">
            {{ __('admin.name_en') }}
        </label>

        <input id="name_en"
               name="translations[en][name]"
               type="text"
               value="{{ old('translations.en.name', $existing?->name) }}"
               required
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">

        @error('translations.en.name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description_en" class="block text-sm font-medium text-stone-700">
            {{ __('admin.description_en') }}
        </label>

        <textarea id="description_en"
                  name="translations[en][description]"
                  rows="3"
                  class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">{{ old('translations.en.description', $existing?->description) }}</textarea>
    </div>

    @php
        $existingAr = isset($item) ? $item->translate('ar') : null;
    @endphp

    <div>
        <label for="name_ar" class="block text-sm font-medium text-stone-700">
            {{ __('admin.name_ar') }}
        </label>

        <input id="name_ar"
               name="translations[ar][name]"
               type="text"
               dir="rtl"
               value="{{ old('translations.ar.name', $existingAr?->name) }}"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <label for="description_ar" class="block text-sm font-medium text-stone-700">
            {{ __('admin.description_ar') }}
        </label>

        <textarea id="description_ar"
                  name="translations[ar][description]"
                  rows="3"
                  dir="rtl"
                  class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">{{ old('translations.ar.description', $existingAr?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">

        <div>
            <label for="price" class="block text-sm font-medium text-stone-700">
                {{ __('admin.price') }}
            </label>

            <input id="price"
                   name="price"
                   type="number"
                   step="0.01"
                   min="0"
                   value="{{ old('price', $item->price ?? '') }}"
                   required
                   class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">

            @error('price')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stock" class="block text-sm font-medium text-stone-700">
                {{ __('admin.base_stock') }}
            </label>

            <input id="stock"
                   name="stock"
                   type="number"
                   min="0"
                   value="{{ old('stock', $item->stock ?? 0) }}"
                   required
                   class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">

            @error('stock')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <div>
        <label for="sku" class="block text-sm font-medium text-stone-700">
            {{ __('admin.sku') }}
        </label>

        <input id="sku"
               name="sku"
               type="text"
               value="{{ old('sku', $item->sku ?? '') }}"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">

        @error('sku')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-stone-700">
            {{ __('admin.image') }}
        </label>

        <input id="image"
               name="image"
               type="file"
               accept="image/*"
               class="mt-1 block w-full text-sm">

        @if (isset($item) && $item->getFirstImageUrl())
            <img src="{{ $item->getFirstImageUrl() }}"
                 class="mt-2 h-16 w-16 rounded-lg object-cover"
                 alt="">
        @endif
    </div>

    <label class="flex items-center gap-2 text-sm text-stone-600">
        <input type="hidden" name="is_active" value="0">

        <input type="checkbox"
               name="is_active"
               value="1"
               @checked(old('is_active', $item->is_active ?? true))
               class="rounded border-stone-300 text-brand-600">

        {{ __('admin.active') }}
    </label>

    <div>
        <span class="block text-sm font-medium text-stone-700">
            أنواع الخصائص
            <span class="text-stone-400">(مثال: Size, Color)</span>
        </span>

        @php
            $existingTypes = isset($item)
                ? $item->attributeTypes()->with('values')->get()
                : collect();
        @endphp

        <div class="mt-2 space-y-4" id="attribute-types">

            @for ($i = 0; $i < max(2, $existingTypes->count() + 1); $i++)

                @php
                    $t = $existingTypes->get($i);
                @endphp

                <div class="rounded-lg border border-stone-200 p-3">

                    <input name="attribute_types[{{ $i }}][name]"
                           placeholder="اسم الخاصية (Size)"
                           value="{{ old("attribute_types.$i.name", $t?->name) }}"
                           class="mb-2 block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">

                    @php
                        $existingValues = $t?->values ?? collect();
                    @endphp

                    @for ($j = 0; $j < max(3, $existingValues->count() + 1); $j++)

                        <input name="attribute_types[{{ $i }}][values][{{ $j }}]"
                               placeholder="قيمة (Small)"
                               value="{{ old("attribute_types.$i.values.$j", $existingValues->get($j)?->value) }}"
                               class="mb-1 block w-full rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">

                    @endfor

                </div>

            @endfor

        </div>

        @if (isset($item))

            <p class="mt-3 text-sm text-stone-500">
                بعد حفظ الخصائص، روح لـ
                <a href="{{ route('admin.items.variants.edit', $item) }}"
                   class="font-medium text-brand-600 hover:underline">
                    إدارة التركيبات والمخزون
                </a>
                عشان تحدد سعر ومخزون كل تركيبة.
            </p>

        @else

            <p class="mt-3 text-sm text-stone-500">
                احفظ المنتج الأول، بعدين هتقدر تدير التركيبات.
            </p>

        @endif

    </div>

    <button type="submit"
            class="rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
        {{ isset($item) ? __('admin.save_changes') : __('admin.new_item') }}
    </button>

</div>
