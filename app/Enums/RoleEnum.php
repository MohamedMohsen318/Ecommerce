<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SuperAdmin = 'super-admin';
    case Support = 'support';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
