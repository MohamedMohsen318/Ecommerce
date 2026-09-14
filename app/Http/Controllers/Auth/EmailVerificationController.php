<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmailVerificationController extends Controller
{
    public function notice(): View|RedirectResponse
    {
        return auth()->user()->hasVerifiedEmail()
            ? redirect()->route('home')
            : view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('home')->with('success', 'Email verified!');
    }

    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Throwable $exception) {
            Log::warning('Email verification notification could not be resent.', [
                'user_id' => $request->user()->id,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'Mailtrap is limiting emails right now. Please wait a minute and try again.',
            ]);
        }

        return back()->with('success', 'Verification link sent.');
    }
}
