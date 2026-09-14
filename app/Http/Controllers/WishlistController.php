<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        return view('wishlist.index', [
            'items' => auth()->user()->wishlistedItems()->with('translations')->get(),
        ]);
    }

    public function toggle(Item $item): RedirectResponse
    {
        auth()->user()->wishlistedItems()->toggle($item->id);

        return back()->with('success', 'Wishlist updated.');
    }
}
