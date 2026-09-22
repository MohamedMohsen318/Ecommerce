@extends('layouts.admin')

@section('title', 'إدارة تركيبات: ' . $item->translate()?->name)

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">
        تركيبات {{ $item->translate()?->name }}
    </h2>

    @if (empty($combinations))
        <p class="text-stone-500">لازم تضيف نوع خاصية واحد على الأقل (وقيمه) في فورم المنتج الأول.</p>
    @else
        <form method="POST" action="{{ route('admin.items.variants.update', $item) }}">
            @csrf @method('PUT')

            <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
                <table class="min-w-full divide-y divide-stone-200 text-sm">
                    <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-4 py-3"></th>
                        <th class="px-4 py-3">التركيبة</th>
                        <th class="px-4 py-3">SKU</th>
                        <th class="px-4 py-3">فرق السعر</th>
                        <th class="px-4 py-3">المخزون</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                    @foreach ($combinations as $i => $combo)
                        @php $existing = $combo['existing']; @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <input type="checkbox" name="variants[{{ $i }}][selected]" value="1"
                                       @checked($existing !== null)
                                       class="rounded border-stone-300 text-brand-600">
                            </td>
                            <td class="px-4 py-3 font-medium text-stone-900">
                                {{ implode(' / ', $combo['labels']) }}
                                @foreach ($combo['value_ids'] as $vid)
                                    <input type="hidden" name="variants[{{ $i }}][value_ids][]" value="{{ $vid }}">
                                @endforeach
                            </td>
                            <td class="px-4 py-3">
                                <input name="variants[{{ $i }}][sku]" value="{{ old("variants.$i.sku", $existing?->sku) }}"
                                       class="w-32 rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                            </td>
                            <td class="px-4 py-3">
                                <input name="variants[{{ $i }}][price_modifier]" type="number" step="0.01"
                                       value="{{ old("variants.$i.price_modifier", $existing?->price_modifier ?? 0) }}"
                                       class="w-24 rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                            </td>
                            <td class="px-4 py-3">
                                <input name="variants[{{ $i }}][stock]" type="number" min="0"
                                       value="{{ old("variants.$i.stock", $existing?->stock ?? 0) }}"
                                       class="w-24 rounded-lg border-stone-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <p class="mt-3 text-sm text-stone-500">التركيبات اللي مش متعلّم عليها Checkbox هتتحذف عند الحفظ.</p>

            <button type="submit" class="mt-4 rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                حفظ التركيبات
            </button>
        </form>
    @endif
@endsection
