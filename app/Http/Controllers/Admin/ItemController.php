<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Services\Admin\ItemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function __construct(private ItemService $itemService) {}

    public function index(): View
    {
        return view('admin.items.index', [
            'items' => $this->itemService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('admin.items.create', [
            'categories' => Category::query()->with('translations')->get(),
        ]);
    }

    public function store(ItemRequest $request): RedirectResponse
    {
        $this->itemService->create($request->validated());

        return redirect()->route('admin.items.index')->with('success', 'Item created.');
    }

    public function edit(Item $item): View
    {
        return view('admin.items.edit', [
            'item' => $item->load('translations', 'variants'),
            'categories' => Category::query()->with('translations')->get(),
        ]);
    }

    public function update(ItemRequest $request, Item $item): RedirectResponse
    {
        $this->itemService->update($item, $request->validated());

        return redirect()->route('admin.items.index')->with('success', 'Item updated.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $this->itemService->delete($item);

        return redirect()->route('admin.items.index')->with('success', 'Item deleted.');
    }
}
