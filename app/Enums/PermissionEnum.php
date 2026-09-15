<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case ViewDashboard = 'view_dashboard';
    case ManageAdmins = 'manage_admins';
    case ManageCategories = 'manage_categories';
    case ManageItems = 'manage_items';
    case ManageReviews = 'manage_reviews';
    case ManageDiscounts = 'manage_discounts';
    case ManageOrders = 'manage_orders';
    case ManageFlashSales = 'manage_flash_sales';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
