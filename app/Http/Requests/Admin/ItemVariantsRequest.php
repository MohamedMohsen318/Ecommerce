<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ItemVariantsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'variants' => ['required', 'array'],
            'variants.*.selected' => ['sometimes', 'boolean'],
            'variants.*.value_ids' => ['required', 'array'],
            'variants.*.value_ids.*' => ['integer', 'exists:item_attribute_values,id'],
            'variants.*.sku' => ['nullable', 'string', 'max:100'],
            'variants.*.price_modifier' => ['nullable', 'numeric'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
