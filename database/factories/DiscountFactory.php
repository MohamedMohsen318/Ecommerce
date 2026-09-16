<?php

namespace Database\Factories;

use App\Enums\DiscountType;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('SAVE##')),
            'type' => DiscountType::Percentage,
            'value' => 10,
            'max_uses' => null,
            'used_count' => 0,
            'expires_at' => null,
            'is_active' => true,
        ];
    }
}
