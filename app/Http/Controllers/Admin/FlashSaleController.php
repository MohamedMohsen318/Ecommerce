<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FlashSaleRequest;
use App\Models\FlashSale;
use App\Models\Item;
use App\Services\Admin\FlashSaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FlashSaleController extends Controller
{
    public function __construct(private FlashSaleService $flashSaleService) {}

    public function index(): View
    {
        return view('admin.flash-sales.index', [
            'flashSales' => $this->flashSaleService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('admin.flash-sales.create', [
            'items' => Item::query()->active()->with('translations')->get(),
        ]);
    }

    public function store(FlashSaleRequest $request): RedirectResponse
    {
        $this->flashSaleService->create($request->validated());

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale created.');
    }

    public function edit(FlashSale $flashSale): View
    {
        return view('admin.flash-sales.edit', [
            'flashSale' => $flashSale->load('items.translations'),
            'items' => Item::query()->active()->with('translations')->get(),
        ]);
    }

    public function update(FlashSaleRequest $request, FlashSale $flashSale): RedirectResponse
    {
        $this->flashSaleService->update($flashSale, $request->validated());

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale updated.');
    }

    public function destroy(FlashSale $flashSale): RedirectResponse
    {
        $this->flashSaleService->delete($flashSale);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale deleted.');
    }
}
