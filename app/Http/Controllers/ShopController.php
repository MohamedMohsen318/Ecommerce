<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ReviewService;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    public function index(): View
    {
        $items = Item::query()
            ->active()
            ->inStock()
            ->with(['category.translations', 'translations', 'variants'])
            ->paginate(12);

        return view('shop.index', ['items' => $items]);
    }

    public function show(Item $item): View
    {
        abort_unless($item->is_active, 404);

        return view('shop.show', [
            'item' => $item->load('translations', 'variants', 'category.translations'),
            'reviews' => $item->reviews()->approved()->with('user')->latest()->get(),
            'comments' => $item->comments()->with('user', 'replies.user')->latest()->get(),
            'canReview' => auth()->check() && $this->reviewService->canReview(auth()->id(), $item->id),
            'isWishlisted' => auth()->check() && auth()->user()->wishlistedItems()->where('item_id', $item->id)->exists(),
        ]);
    }
}
