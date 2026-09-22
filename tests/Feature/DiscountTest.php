<?php

namespace Tests\Feature;

use App\Enums\DiscountType;
use App\Enums\RoleEnum;
use App\Models\Admin;
use App\Models\Discount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class DiscountTest extends TestCase
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

    protected function url(string $path): string
    {
        return 'http://'.$this->tenant->id.'.test'.$path;
    }

    protected function actingAsSuperAdmin(): Admin
    {
        $admin = Admin::factory()->create();
        $admin->assignRole(RoleEnum::SuperAdmin->value);
        $this->actingAs($admin, 'admins');

        return $admin;
    }

    public function test_admin_can_create_a_discount(): void
    {
        $this->actingAsSuperAdmin();

        $response = $this->post($this->url(route('admin.discounts.store', [], false)), [
            'code' => 'save20',
            'type' => DiscountType::Percentage->value,
            'value' => 20,
            'is_active' => 1,
        ]);

        $response->assertRedirect($this->url(route('admin.discounts.index', [], false)));
        $this->assertDatabaseHas('discounts', ['code' => 'SAVE20'], 'tenant');
    }

    public function test_duplicate_discount_code_is_rejected(): void
    {
        $this->actingAsSuperAdmin();
        Discount::factory()->create(['code' => 'EXISTING']);

        $response = $this->post($this->url(route('admin.discounts.store', [], false)), [
            'code' => 'EXISTING',
            'type' => DiscountType::Fixed->value,
            'value' => 10,
        ]);

        $response->assertSessionHasErrors('code');
    }
}
