<?php

namespace App\Services\Admin;

use App\Enums\RoleEnum;
use App\Models\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AdminService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Admin::with('roles')->latest()->paginate($perPage);
    }

    public function create(array $data): Admin
    {
        return DB::transaction(function () use ($data) {
            $admin = Admin::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $admin->syncRoles($data['roles']);

            return $admin;
        });
    }

    public function update(Admin $admin, array $data): Admin
    {
        return DB::transaction(function () use ($admin, $data) {
            $admin->update(array_filter([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'] ?? null,
            ], fn ($value) => $value !== null));

            $admin->syncRoles($data['roles']);

            return $admin;
        });
    }

    public function delete(Admin $admin, Admin $actingAdmin): void
    {
        if ($admin->is($actingAdmin)) {
            throw new \RuntimeException("You can't delete your own account.");
        }

        $isLastSuperAdmin = $admin->hasRole(RoleEnum::SuperAdmin->value)
            && Admin::role(RoleEnum::SuperAdmin->value)->count() <= 1;

        if ($isLastSuperAdmin) {
            throw new \RuntimeException('At least one super-admin must remain.');
        }

        $admin->delete();
    }
}
