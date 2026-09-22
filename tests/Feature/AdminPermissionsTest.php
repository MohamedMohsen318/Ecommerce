<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class AdminPermissionsTest extends TestCase
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

    public function test_support_role_cannot_access_admin_management(): void
    {
        $support = Admin::factory()->create();
        $support->assignRole(RoleEnum::Support->value);

        $response = $this->actingAs($support, 'admins')
            ->get($this->url(route('admin.admins.index', [], false)));

        $response->assertForbidden();
    }

    public function test_support_role_can_access_orders(): void
    {
        $support = Admin::factory()->create();
        $support->assignRole(RoleEnum::Support->value);

        $response = $this->actingAs($support, 'admins')
            ->get($this->url(route('admin.orders.index', [], false)));

        $response->assertOk();
    }

    public function test_super_admin_cannot_delete_their_own_account(): void
    {
        $admin = Admin::factory()->create();
        $admin->assignRole(RoleEnum::SuperAdmin->value);

        $response = $this->actingAs($admin, 'admins')
            ->delete($this->url(route('admin.admins.destroy', $admin, false)));

        $response->assertSessionHasErrors('admin');
        $this->assertDatabaseHas('admins', ['id' => $admin->id], 'tenant');
    }
}
