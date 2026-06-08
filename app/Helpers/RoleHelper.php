<?php

namespace App\Helpers;

class RoleHelper
{
    public static function isSuperAdmin(): bool
    {
        return auth()->check() && auth()->user()->role === 'super_admin';
    }

    public static function isAdmin(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin']);
    }

    public static function isAdminGudang(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin_gudang']);
    }

    public static function canSeeProfit(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'owner']);
    }

    public static function canManageAdmins(): bool
    {
        return auth()->check() && auth()->user()->role === 'super_admin';
    }
}