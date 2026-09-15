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
            ->with(['category.translations', 'translations', 'variants', 'media', 'flashSales'])
            ->paginate(12);

        return view('shop.index', ['items' => $items]);
    }

    public function show(Item $item): View
    {
        abort_unless($item->is_active, 404);

        return view('shop.show', [
            'item' => $item->load('translations', 'variants', 'category.translations', 'flashSales'),

        ]);
    }
}
