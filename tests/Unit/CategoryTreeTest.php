<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class CategoryTreeTest extends TestCase
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

    public function test_inactive_parent_hides_its_active_children_from_tree(): void
    {
        $parent = Category::factory()->create(['is_active' => false]);
        Category::factory()->create(['parent_id' => $parent->id, 'is_active' => true]);

        $tree = Category::tree();

        $this->assertCount(0, $tree);
    }

    public function test_active_parent_shows_only_active_children(): void
    {
        $parent = Category::factory()->create(['is_active' => true]);
        Category::factory()->create(['parent_id' => $parent->id, 'is_active' => true]);
        Category::factory()->create(['parent_id' => $parent->id, 'is_active' => false]);

        $tree = Category::tree();

        $this->assertCount(1, $tree);
        $this->assertCount(1, $tree->first()->children);
    }
}
