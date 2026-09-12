<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case ViewDashboard = 'view_dashboard';
    case ManageAdmins = 'manage_admins';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
