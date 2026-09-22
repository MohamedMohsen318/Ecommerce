<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Discount;
use App\Models\FlashSale;
use App\Models\Item;
use App\Models\ItemVariant;
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
        $discount = Discount::query()->firstOrCreate(
            ['code' => 'WELCOME10'],
            Discount::factory()->raw(['code' => 'WELCOME10'])
        );

        $electronics = Category::query()->firstOrCreate(
            ['parent_id' => null],
            Category::factory()->raw()
        );
        if (! $electronics->translate('en')) {
            $electronics->setTranslation('en', [
                'name' => 'Electronics',
                'description' => 'Phones, laptops, and more.',
            ]);
        }

        $laptops = Category::query()->firstOrCreate(
            ['parent_id' => $electronics->id],
            Category::factory()->raw()
        );
        if (! $laptops->translate('en')) {
            $laptops->setTranslation('en', [
                'name' => 'Laptops',
                'description' => 'Portable computers.',
            ]);
        }

        $item = Item::query()->where('category_id', $laptops->id)->first();

        if (! $item) {
            $item = Item::factory()->create(['category_id' => $laptops->id]);
            $item->setTranslation('en', [
                'name' => 'Aurora 14" Laptop',
                'description' => 'A lightweight everyday laptop.',
            ]);

            $storageType = $item->attributeTypes()->create(['name' => 'Storage', 'order' => 0]);
            $storage256 = $storageType->values()->create(['value' => '256GB', 'order' => 0]);
            $storage512 = $storageType->values()->create(['value' => '512GB', 'order' => 1]);

            $variant256 = $item->variants()->create([
                'sku' => null,
                'price_modifier' => 0,
                'stock' => 10,
                'combination_hash' => ItemVariant::hashFor([$storage256->id]),
            ]);
            $variant256->values()->attach($storage256->id);

            $variant512 = $item->variants()->create([
                'sku' => null,
                'price_modifier' => 80,
                'stock' => 4,
                'combination_hash' => ItemVariant::hashFor([$storage512->id]),
            ]);
            $variant512->values()->attach($storage512->id);
        }

        $customer = User::query()->firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => bcrypt('password'),
            ]
        );

        $order = Order::query()->where('user_id', $customer->id)->first();

        if (! $order) {
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
        }

        $flashSale = FlashSale::query()->firstOrCreate(
            ['name' => 'Weekend Flash Sale'],
            [
                'starts_at' => now()->subHour(),
                'ends_at' => now()->addDays(2),
                'is_active' => true,
            ]
        );

        if (! $flashSale->items()->where('item_id', $item->id)->exists()) {
            $flashSale->items()->attach($item->id, [
                'sale_price' => round((float) $item->price * 0.8, 2),
            ]);
        }

        ProductReview::query()->firstOrCreate(
            ['item_id' => $item->id, 'user_id' => $customer->id],
            [
                'order_id' => $order->id,
                'rating' => 5,
                'body' => 'Great everyday laptop, very happy with it.',
                'is_approved' => true,
            ]
        );

        $question = ProductComment::query()->firstOrCreate(
            ['item_id' => $item->id, 'user_id' => $customer->id, 'parent_id' => null],
            ['body' => 'Does the 512GB version ship with more RAM too?']
        );

        ProductComment::query()->firstOrCreate(
            ['item_id' => $item->id, 'user_id' => $customer->id, 'parent_id' => $question->id],
            ['body' => 'No, RAM is the same across storage options.']
        );

        $customer->addresses()->firstOrCreate(
            ['label' => 'Home'],
            ['line' => '123 Demo Street', 'is_default' => true]
        );

        if (! $customer->wishlistedItems()->where('item_id', $item->id)->exists()) {
            $customer->wishlistedItems()->attach($item->id);
        }
    }
}
