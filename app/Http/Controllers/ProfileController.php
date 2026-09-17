<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        auth()->user()->update(array_filter([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'] ?? null,
        ], fn ($value) => $value !== null));

        return back()->with('success', 'Profile updated.');
    }
}
