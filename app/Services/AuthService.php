<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user);

        try {
            event(new Registered($user));
        } catch (Throwable $exception) {
            Log::warning('Email verification notification could not be sent.', [
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return $user;
    }

    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
