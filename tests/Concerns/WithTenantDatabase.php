<?php

namespace Tests\Concerns;

use App\Models\Tenant;
use Illuminate\Support\Str;

trait WithTenantDatabase
{
    protected Tenant $tenant;

    protected function setUpTenant(): void
    {
        $this->tenant = Tenant::create(['id' => 'testing-'.strtolower(Str::random(8))]);
        $this->tenant->domains()->create(['domain' => $this->tenant->id.'.test']);

        tenancy()->initialize($this->tenant);
    }

    protected function tearDownTenant(): void
    {
        tenancy()->end();

        $this->tenant->delete();
    }
}
