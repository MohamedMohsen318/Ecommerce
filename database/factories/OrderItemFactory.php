<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'item_id' => Item::factory(),
            'item_name' => fake()->words(3, true),
            'unit_price' => fake()->randomFloat(2, 5, 200),
            'quantity' => fake()->numberBetween(1, 3),
        ];
    }
}
