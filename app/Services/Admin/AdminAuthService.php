<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;

class AdminAuthService
{
    public function login(array $credentials): bool
    {
        return Auth::guard('admins')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);
    }

    public function logout(): void
    {
        Auth::guard('admins')->logout();
    }
}
