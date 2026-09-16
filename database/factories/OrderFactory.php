<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 500);

        return [
            'user_id' => User::factory(),
            'status' => OrderStatus::Pending,
            'subtotal' => $subtotal,
            'discount_amount' => 0,
            'points_redeemed' => 0,
            'points_discount_amount' => 0,
            'total' => $subtotal,
            'shipping_address' => fake()->address(),
        ];
    }
}
