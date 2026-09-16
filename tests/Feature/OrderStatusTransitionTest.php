<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\OrderStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class OrderStatusTransitionTest extends TestCase
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

    protected function makeOrder(OrderStatus $status): Order
    {
        return Order::factory()->create([
            'user_id' => User::factory(),
            'status' => $status,
            'subtotal' => 50,
            'total' => 50,
        ]);
    }

    public function test_pending_can_move_to_confirmed(): void
    {
        $order = $this->makeOrder(OrderStatus::Pending);

        app(OrderStatusService::class)->transition($order, OrderStatus::Confirmed);

        $this->assertEquals(OrderStatus::Confirmed, $order->fresh()->status);
    }

    public function test_pending_cannot_jump_straight_to_delivered(): void
    {
        $order = $this->makeOrder(OrderStatus::Pending);

        $this->expectException(\RuntimeException::class);

        app(OrderStatusService::class)->transition($order, OrderStatus::Delivered);
    }

    public function test_delivered_is_a_final_state(): void
    {
        $order = $this->makeOrder(OrderStatus::Delivered);

        $this->expectException(\RuntimeException::class);

        app(OrderStatusService::class)->transition($order, OrderStatus::Cancelled);
    }

    public function test_every_transition_writes_a_status_history_row(): void
    {
        $order = $this->makeOrder(OrderStatus::Pending);

        app(OrderStatusService::class)->transition($order, OrderStatus::Confirmed, note: 'Payment received');

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status' => OrderStatus::Confirmed->value,
            'note' => 'Payment received',
        ], 'tenant');
    }

    public function test_cancelling_restores_stock(): void
    {
        $item = Item::factory()->create(['stock' => 3]);
        $order = $this->makeOrder(OrderStatus::Confirmed);
        OrderItem::factory()->create([
            'order_id' => $order->id, 'item_id' => $item->id,
            'quantity' => 2, 'unit_price' => 10, 'item_name' => 'Test item',
        ]);

        app(OrderStatusService::class)->transition($order, OrderStatus::Cancelled);

        $this->assertEquals(5, $item->fresh()->stock);
    }
}
