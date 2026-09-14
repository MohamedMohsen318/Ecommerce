<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    public function store(Request $request, Item $item): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $this->reviewService->create(auth()->id(), $item->id, $data['rating'], $data['body'] ?? null);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['review' => $e->getMessage()]);
        }

        return back()->with('success', "Thanks for your review — it'll show once approved.");
    }
}
