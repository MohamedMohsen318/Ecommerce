<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
    ) {}

    public function create(Request $request): View
    {
        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());

        return view('checkout.create', [
            'cart' => $cart->load('items.item.translations', 'items.variant'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
        ]);

        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());

        try {
            $order = $this->orderService->checkout($cart, auth()->id(), $data['shipping_address']);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()])->withInput();
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order placed!');
    }
}
