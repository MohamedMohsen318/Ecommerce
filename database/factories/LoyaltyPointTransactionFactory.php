<?php

namespace Database\Factories;

use App\Models\LoyaltyPointTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoyaltyPointTransactionFactory extends Factory
{
    protected $model = LoyaltyPointTransaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_id' => null,
            'points' => fake()->numberBetween(1, 100),
            'reason' => 'order_placed',
        ];
    }
}
