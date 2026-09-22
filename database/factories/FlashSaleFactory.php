<?php

namespace Database\Factories;

use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlashSaleFactory extends Factory
{
    protected $model = FlashSale::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDays(2),
            'is_active' => true,
        ];
    }
}

