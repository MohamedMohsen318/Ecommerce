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
        $guardName = AuthGuard::Admins->value;

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        foreach (PermissionEnum::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => $guardName,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin Role
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => RoleEnum::SuperAdmin->value,
            'guard_name' => $guardName,
        ]);

        $superAdmin->syncPermissions(
            PermissionEnum::values()
        );

        /*
        |--------------------------------------------------------------------------
        | Support Role
        |--------------------------------------------------------------------------
        */

        $support = Role::firstOrCreate([
            'name' => RoleEnum::Support->value,
            'guard_name' => $guardName,
        ]);

        $support->syncPermissions([
            PermissionEnum::ViewDashboard->value,
            PermissionEnum::ManageOrders->value,
            PermissionEnum::ManageComments->value,
            PermissionEnum::ManageReviews->value,
        ]);
    }
}
