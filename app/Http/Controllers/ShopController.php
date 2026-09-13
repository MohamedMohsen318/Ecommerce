<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $items = Item::query()
            ->active()
            ->inStock()
            ->with(['category.translations', 'translations', 'variants'])
            ->paginate(12);

        return view('shop.index', ['items' => $items]);
    }
}
