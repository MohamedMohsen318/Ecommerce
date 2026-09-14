<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.reviews.index', [
            'reviews' => ProductReview::with(['item.translations', 'user'])->latest()->paginate(15),
        ]);
    }

    public function approve(ProductReview $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Review approved.');
    }

    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
