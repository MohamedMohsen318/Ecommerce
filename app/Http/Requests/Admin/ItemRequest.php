<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $item = $this->route('item');

        return [
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('items', 'sku')->ignore($item?->id)],
            'is_active' => ['sometimes', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.en.name' => ['required', 'string', 'max:255'],
            'translations.en.description' => ['nullable', 'string'],
            'translations.ar.name' => ['nullable', 'string', 'max:255'],
            'translations.ar.description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['nullable', 'string', 'max:100'],
            'variants.*.value' => ['nullable', 'string', 'max:100'],
            'variants.*.price_modifier' => ['nullable', 'numeric'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
