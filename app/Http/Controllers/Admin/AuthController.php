<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Services\Admin\AdminAuthService;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AdminAuthService $authService
    ) {}

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        if (! $this->authService->login($request->validated())) {
            return back()
                ->withErrors(['email' => __('auth.invalid_credentials')])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', __('auth.welcome_back'));
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login.create');
    }
}
