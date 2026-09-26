<?php

namespace App\Services\Admin;

use App\Enums\AuthGuard;
use Illuminate\Support\Facades\Auth;

class AdminAuthService
{
    public function login(array $credentials, bool $remember = false): bool {
        return Auth::guard(AuthGuard::Admins->value)->attempt(
            [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ],
            $remember
        );
    }
    public function logout(): void
    {
        Auth::guard(AuthGuard::Admins->value)->logout();
    }
}
