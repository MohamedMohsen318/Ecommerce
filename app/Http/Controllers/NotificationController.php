<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('notifications.index', [
            'notifications' => auth()->user()->notifications()->paginate(15),
        ]);
    }

    public function markAsRead(string $notificationId): RedirectResponse
    {
        auth()->user()->notifications()->whereKey($notificationId)->first()?->markAsRead();

        return back();
    }
}
