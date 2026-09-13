<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected CartService $cartService,
    ) {}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $guestSessionId = $request->session()->getId();

        $user = $this->authService->register($request->validated());

        $this->cartService->mergeGuestCartIntoUser($guestSessionId, $user->id);

        return redirect()->route('home')
            ->with('success', __('auth.account_created'));
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $guestSessionId = $request->session()->getId();

        $ok = $this->authService->login(
            $request->validated(),
            $request->boolean('remember')
        );

        if (! $ok) {
            return back()
                ->withErrors(['email' => __('auth.invalid_credentials')])
                ->onlyInput('email');
        }

        $this->cartService->mergeGuestCartIntoUser($guestSessionId, auth()->id());

        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', __('auth.welcome_back'));
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home');
    }
}
