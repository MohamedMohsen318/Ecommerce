<?php

namespace Tests\Feature;

use App\Enums\DiscountType;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Item;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase, WithTenantDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenant();
    }

    protected function tearDown(): void
    {
        $this->tearDownTenant();
        parent::tearDown();
    }

    public function test_checkout_creates_an_order_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['price' => 20, 'stock' => 5]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cart->items()->create(['item_id' => $item->id, 'quantity' => 2]);

        $order = app(OrderService::class)->checkout($cart->fresh('items'), $user->id, '123 Test St');

        $this->assertEquals(40.00, $order->subtotal);
        $this->assertEquals(3, $item->fresh()->stock);
        $this->assertCount(1, $order->items);
        $this->assertEquals(20.00, $order->items->first()->unit_price);
    }

    public function test_checkout_fails_when_requested_quantity_exceeds_stock(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['stock' => 1]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cart->items()->create(['item_id' => $item->id, 'quantity' => 2]);

        $this->expectException(\RuntimeException::class);

        app(OrderService::class)->checkout($cart->fresh('items'), $user->id, '123 Test St');
    }

    public function test_checkout_clears_the_cart_on_success(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['stock' => 5]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cart->items()->create(['item_id' => $item->id, 'quantity' => 1]);

        app(OrderService::class)->checkout($cart->fresh('items'), $user->id, '123 Test St');

        $this->assertCount(0, $cart->fresh('items')->items);
    }

    public function test_checkout_applies_a_valid_discount_code(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['price' => 100, 'stock' => 5]);
        Discount::factory()->create([
            'code' => 'TENOFF', 'type' => DiscountType::Fixed, 'value' => 10,
        ]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cart->items()->create(['item_id' => $item->id, 'quantity' => 1]);

        $order = app(OrderService::class)->checkout($cart->fresh('items'), $user->id, '123 Test St', 'TENOFF');

        $this->assertEquals(10.00, $order->discount_amount);
        $this->assertEquals(90.00, $order->total);
    }

    public function test_checkout_rejects_an_expired_discount_code(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['stock' => 5]);
        Discount::factory()->create(['code' => 'EXPIRED', 'expires_at' => now()->subDay()]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cart->items()->create(['item_id' => $item->id, 'quantity' => 1]);

        $this->expectException(\RuntimeException::class);

        app(OrderService::class)->checkout($cart->fresh('items'), $user->id, '123 Test St', 'EXPIRED');
    }
}
