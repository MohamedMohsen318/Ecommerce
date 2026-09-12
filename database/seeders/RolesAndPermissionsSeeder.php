<?php

namespace Database\Seeders;

use App\Enums\AuthGuard;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => AuthGuard::Admins->value,
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => RoleEnum::SuperAdmin->value,
            'guard_name' => AuthGuard::Admins->value,
        ]);
        $superAdmin->syncPermissions(PermissionEnum::values());
    }
}
