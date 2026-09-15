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
            'cart' => $cart->load('items.item.translations', 'items.item.flashSales', 'items.variant'),
            'pointsBalance' => auth()->user()->loyaltyPointsBalance(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
            'discount_code' => ['nullable', 'string', 'max:50'],
            'redeem_points' => ['nullable', 'integer', 'min:0'],
        ]);

        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());

        try {
            $order = $this->orderService->checkout(
                $cart,
                auth()->id(),
                $data['shipping_address'],
                $data['discount_code'] ?? null,
                $data['redeem_points'] ?? 0,
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()])->withInput();
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order placed!');
    }
}
