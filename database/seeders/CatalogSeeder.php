<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Discount;
use App\Models\FlashSale;
use App\Models\Item;
use App\Models\LoyaltyPointTransaction;
use App\Models\Order;
use App\Models\ProductComment;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        Discount::factory()->create(['code' => 'WELCOME10']);

        $electronics = Category::factory()->create();
        $electronics->setTranslation('en', [
            'name' => 'Electronics',
            'description' => 'Phones, laptops, and more.',
        ]);

        $laptops = Category::factory()->create(['parent_id' => $electronics->id]);
        $laptops->setTranslation('en', [
            'name' => 'Laptops',
            'description' => 'Portable computers.',
        ]);

        $item = Item::factory()->create(['category_id' => $laptops->id]);
        $item->setTranslation('en', [
            'name' => 'Aurora 14" Laptop',
            'description' => 'A lightweight everyday laptop.',
        ]);
        $item->variants()->create(['name' => 'Storage', 'value' => '256GB', 'price_modifier' => 0, 'stock' => 10]);
        $item->variants()->create(['name' => 'Storage', 'value' => '512GB', 'price_modifier' => 80, 'stock' => 4]);

        $customer = User::factory()->create([
            'name' => 'Demo Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'status' => OrderStatus::Confirmed,
            'subtotal' => $item->price,
            'discount_amount' => 0,
            'points_redeemed' => 0,
            'points_discount_amount' => 0,
            'total' => $item->price,
            'shipping_address' => '123 Demo Street',
        ]);
        $order->items()->create([
            'item_id' => $item->id,
            'item_name' => $item->translate('en')->name,
            'unit_price' => $item->price,
            'quantity' => 1,
        ]);
        $order->statusHistory()->create(['status' => OrderStatus::Confirmed]);


        LoyaltyPointTransaction::create([
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'points' => (int) floor((float) $order->total),
            'reason' => 'order_placed',
        ]);

        $flashSale = FlashSale::create([
            'name' => 'Weekend Flash Sale',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDays(2),
            'is_active' => true,
        ]);
        $flashSale->items()->attach($item->id, ['sale_price' => round((float) $item->price * 0.8, 2)]);

        ProductReview::create([
            'item_id' => $item->id,
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'rating' => 5,
            'body' => 'Great everyday laptop, very happy with it.',
            'is_approved' => true,
        ]);

        $question = ProductComment::create([
            'item_id' => $item->id,
            'user_id' => $customer->id,
            'body' => 'Does the 512GB version ship with more RAM too?',
        ]);
        ProductComment::create([
            'item_id' => $item->id,
            'user_id' => $customer->id,
            'parent_id' => $question->id,
            'body' => 'No, RAM is the same across storage options.',
        ]);

        $customer->addresses()->create([
            'label' => 'Home',
            'line' => '123 Demo Street',
            'is_default' => true,
        ]);

        $customer->wishlistedItems()->attach($item->id);
    }
}
