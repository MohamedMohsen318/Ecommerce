<?php

namespace Tests\Unit;

use App\Enums\DiscountType;
use App\Models\Discount;
use Tests\TestCase;

class DiscountTest extends TestCase
{
    public function test_fixed_discount_never_exceeds_subtotal(): void
    {
        $discount = new Discount(['type' => DiscountType::Fixed, 'value' => 50]);

        $this->assertEquals(30.0, $discount->amountFor(30));
        $this->assertEquals(50.0, $discount->amountFor(100));
    }

    public function test_percentage_discount_rounds_to_cents(): void
    {
        $discount = new Discount(['type' => DiscountType::Percentage, 'value' => 15]);

        $this->assertEquals(15.0, $discount->amountFor(100));
        $this->assertEquals(14.93, $discount->amountFor(99.53));
    }

    public function test_inactive_discount_is_not_valid(): void
    {
        $discount = new Discount(['is_active' => false]);

        $this->assertFalse($discount->isValid());
    }

    public function test_expired_discount_is_not_valid(): void
    {
        $discount = new Discount(['is_active' => true, 'expires_at' => now()->subDay()]);

        $this->assertFalse($discount->isValid());
    }

    public function test_discount_at_max_uses_is_not_valid(): void
    {
        $discount = new Discount(['is_active' => true, 'max_uses' => 5, 'used_count' => 5]);

        $this->assertFalse($discount->isValid());
    }

    public function test_discount_under_max_uses_is_valid(): void
    {
        $discount = new Discount(['is_active' => true, 'max_uses' => 5, 'used_count' => 4]);

        $this->assertTrue($discount->isValid());
    }

    public function test_discount_with_no_expiry_or_max_uses_is_valid(): void
    {
        $discount = new Discount(['is_active' => true, 'max_uses' => null, 'expires_at' => null]);

        $this->assertTrue($discount->isValid());
    }
}
