<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ReviewService;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    public function index(Request $request): View
    {
        $items = Item::query()
            ->active()
            ->inStock()
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category'));
            })
            ->with(['category.translations', 'translations', 'variants', 'media', 'flashSales'])
            ->paginate(12);

        return view('shop.index', ['items' => $items]);
    }

    public function show(Item $item): View
    {
        abort_unless($item->is_active, 404);

        $item->load('translations', 'attributeTypes.values', 'variants.values', 'category.translations', 'flashSales');

        $user = auth()->user();
        $canReview = $user
            ? $this->reviewService->canReview($user->id, $item->id)
            : false;

        $isWishlisted = $user
            ? $user->wishlistedItems()->whereKey($item->id)->exists()
            : false;

        return view('shop.show', [
            'item' => $item,
            'canReview' => $canReview,
            'isWishlisted' => $isWishlisted,
            'reviews' => $item->reviews()
                ->approved()
                ->with('user')
                ->latest()
                ->get(),
            'comments' => $item->comments()
                ->with(['user', 'replies.user'])
                ->latest()
                ->get(),
        ]);
    }
}
