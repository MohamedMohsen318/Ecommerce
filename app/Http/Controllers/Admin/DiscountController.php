<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiscountRequest;
use App\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(): View
    {
        return view('admin.discounts.index', [
            'discounts' => Discount::latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.discounts.create');
    }

    public function store(DiscountRequest $request): RedirectResponse
    {
        Discount::create([
            ...$request->validated(),
            'code' => strtoupper($request->validated('code')),
            'used_count' => 0,
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Discount created.');
    }

    public function edit(Discount $discount): View
    {
        return view('admin.discounts.edit', ['discount' => $discount]);
    }

    public function update(DiscountRequest $request, Discount $discount): RedirectResponse
    {
        $discount->update([
            ...$request->validated(),
            'code' => strtoupper($request->validated('code')),
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Discount updated.');
    }

    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('success', 'Discount deleted.');
    }
}
