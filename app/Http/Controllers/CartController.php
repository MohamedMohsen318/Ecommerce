<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(Request $request): View
    {
        $cart = $this->cartService
            ->currentCart(auth()->id(), $request->session()->getId())
            ->load('items.item.translations', 'items.item.flashSales', 'items.variant');

        return view('cart.index', ['cart' => $cart]);
    }
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'item_attribute_id' => ['nullable', 'integer', 'exists:item_attributes,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = Item::findOrFail($data['item_id']);

        if (! empty($data['item_attribute_id']) && ! $item->variants()->whereKey($data['item_attribute_id'])->exists()) {
            return back()->withErrors(['item_attribute_id' => 'Invalid option for this item.']);
        }

        if ($item->variants()->exists() && empty($data['item_attribute_id'])) {
            return back()->withErrors(['item_attribute_id' => 'Please choose an option.']);
        }

        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());
        $this->cartService->addItem($cart, $item->id, $data['item_attribute_id'] ?? null, $data['quantity']);

        return back()->with('success', 'Added to cart.');
    }


    public function update(Request $request, int $cartItem): RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1']]);

        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());
        $this->cartService->updateQuantity($cart, $cartItem, $data['quantity']);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, int $cartItem): RedirectResponse
    {
        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());
        $this->cartService->removeItem($cart, $cartItem);

        return back()->with('success', 'Item removed.');
    }
}
