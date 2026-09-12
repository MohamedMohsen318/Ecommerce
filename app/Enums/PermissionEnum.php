<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case ViewDashboard = 'view_dashboard';
    case ManageAdmins = 'manage_admins';
    case ManageCategories = 'manage_categories';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
