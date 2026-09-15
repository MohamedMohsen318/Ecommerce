<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FlashSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
            'items' => ['nullable', 'array'],
            'items.*.item_id' => ['nullable', 'integer', 'exists:items,id'],
            'items.*.sale_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
