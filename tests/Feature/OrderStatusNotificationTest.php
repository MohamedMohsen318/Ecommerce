<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use App\Services\OrderStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class OrderStatusNotificationTest extends TestCase
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

    public function test_status_transition_sends_notification_to_customer(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Pending,
            'subtotal' => 50,
            'total' => 50,
        ]);

        app(OrderStatusService::class)->transition($order, OrderStatus::Confirmed);

        Notification::assertSentTo(
            $user,
            OrderStatusUpdated::class,
            fn ($notification) => $notification->order->id === $order->id
                && $notification->status === OrderStatus::Confirmed
        );
    }

    public function test_notification_is_queued(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $notification = new OrderStatusUpdated($order, OrderStatus::Confirmed);

        $this->assertInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class, $notification);
    }

    public function test_notification_mail_content_includes_order_number_and_status(): void
    {
        $user = User::factory()->create(['name' => 'Jane Doe']);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $notification = new OrderStatusUpdated($order, OrderStatus::Shipped);
        $mail = $notification->toMail($user);

        $this->assertStringContainsString((string) $order->id, $mail->subject);
        $this->assertStringContainsString('Shipped', $mail->subject);
    }
}
