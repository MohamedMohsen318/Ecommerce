<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
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

    public function test_user_can_review_a_purchased_delivered_item(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => OrderStatus::Delivered]);
        OrderItem::factory()->create(['order_id' => $order->id, 'item_id' => $item->id]);

        $review = app(ReviewService::class)->create($user->id, $item->id, 5, 'Great!');

        $this->assertDatabaseHas('product_reviews', ['id' => $review->id, 'rating' => 5], 'tenant');
    }

    public function test_user_cannot_review_item_they_never_purchased(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->expectException(\RuntimeException::class);

        app(ReviewService::class)->create($user->id, $item->id, 5, 'Fake review');
    }

    public function test_user_cannot_review_same_item_twice(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => OrderStatus::Delivered]);
        OrderItem::factory()->create(['order_id' => $order->id, 'item_id' => $item->id]);

        app(ReviewService::class)->create($user->id, $item->id, 5, 'First');

        $this->expectException(\RuntimeException::class);
        app(ReviewService::class)->create($user->id, $item->id, 4, 'Second');
    }
}
