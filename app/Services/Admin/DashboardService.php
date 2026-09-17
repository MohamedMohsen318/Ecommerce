<?php

namespace App\Services\Admin;

use App\Enums\RoleEnum;
use App\Models\Admin;
use App\Models\User;

class DashboardService
{
    /**
     * @return array<string, int>
     */
    public function stats(): array
    {
        return [
            'admins_count' => Admin::count(),
            'customers_count' => User::count(),
            'super_admins_count' => Admin::role(RoleEnum::SuperAdmin->value)->count(),
            'support_count' => Admin::role(RoleEnum::Support->value)->count(),
        ];
    }
}
