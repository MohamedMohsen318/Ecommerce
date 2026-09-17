<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use Illuminate\View\View;

class DealsController extends Controller
{
    public function index(): View
    {
        return view('deals.index', [
            'flashSales' => FlashSale::running()
                ->with(['items' => fn ($q) => $q->active()->with('translations', 'media')])
                ->get()
                ->filter(fn ($sale) => $sale->items->isNotEmpty()),
        ]);
    }
}
