<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Item;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function index(Request $request): View
    {
        $cart = $this->cartService
            ->currentCart(
                auth()->id(),
                $request->session()->getId()
            )
            ->load(
                'items.item.translations',
                'items.item.flashSales',
                'items.variant.values.type'
            );

        return view('cart.index', [
            'cart' => $cart,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_id' => [
                'required',
                'integer',
                'exists:items,id',
            ],

            'item_variant_id' => [
                'nullable',
                'integer',
                'exists:item_variants,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $item = Item::findOrFail($data['item_id']);

        $variant = null;

        if (! empty($data['item_variant_id'])) {
            $variant = $item
                ->variants()
                ->whereKey($data['item_variant_id'])
                ->first();

            if (! $variant) {
                return back()->withErrors([
                    'item_variant_id' => 'Invalid option for this item.',
                ]);
            }
        } elseif ($item->variants()->exists()) {
            return back()->withErrors([
                'item_variant_id' => 'Please choose an option.',
            ]);
        }

        $cart = $this->cartService->currentCart(
            auth()->id(),
            $request->session()->getId()
        );

        $existingQty = $cart->items()
            ->where('item_id', $item->id)
            ->where(
                'item_variant_id',
                $data['item_variant_id'] ?? null
            )
            ->value('quantity') ?? 0;

        $availableStock = $variant
            ? $variant->stock
            : $item->stock;

        $requestedQuantity = $existingQty + $data['quantity'];

        if ($availableStock < $requestedQuantity) {
            return back()->withErrors([
                'quantity' => 'Not enough stock available.',
            ]);
        }

        $this->cartService->addItem(
            $cart,
            $item->id,
            $data['item_variant_id'] ?? null,
            $data['quantity']
        );

        return back()->with(
            'success',
            'Added to cart.'
        );
    }

    public function update(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {
        $data = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cart = $this->cartService->currentCart(
            auth()->id(),
            $request->session()->getId()
        );

        abort_unless(
            $cartItem->cart_id === $cart->id,
            404
        );

        $this->cartService->updateQuantity(
            $cart,
            $cartItem->id,
            $data['quantity']
        );

        return back()->with(
            'success',
            'Cart updated.'
        );
    }

    public function destroy(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {
        $cart = $this->cartService->currentCart(
            auth()->id(),
            $request->session()->getId()
        );

        abort_unless(
            $cartItem->cart_id === $cart->id,
            404
        );

        $this->cartService->removeItem(
            $cart,
            $cartItem->id
        );

        return back()->with(
            'success',
            'Item removed.'
        );
    }
}
