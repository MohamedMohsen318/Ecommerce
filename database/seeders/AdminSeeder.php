<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@store1.test'],
            [
                'name' => 'Super Admin',
                'password' => 'password123',
            ]
        );

        $admin->assignRole(RoleEnum::SuperAdmin->value);
    }
}
