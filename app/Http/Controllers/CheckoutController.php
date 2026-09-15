<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\AddressService;
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
        private AddressService $addressService,
    ) {}

    public function create(Request $request): View
    {
        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());

        return view('checkout.create', [
            'cart' => $cart->load('items.item.translations', 'items.item.flashSales', 'items.variant'),
            'addresses' => auth()->user()->addresses()->latest()->get(),
            'pointsBalance' => auth()->user()->loyaltyPointsBalance(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'address_choice' => ['required', 'string'],
            'shipping_address' => ['required_if:address_choice,new', 'nullable', 'string', 'max:500'],
            'save_address' => ['sometimes', 'boolean'],
            'discount_code' => ['nullable', 'string', 'max:50'],
            'redeem_points' => ['nullable', 'integer', 'min:0'],
        ]);

        $shippingAddress = $data['address_choice'] === 'new'
            ? $data['shipping_address']
            : Address::query()->where('user_id', auth()->id())->find($data['address_choice'])?->line;

        if (! $shippingAddress) {
            return back()->withErrors(['shipping_address' => 'Please choose or enter a shipping address.'])->withInput();
        }

        if ($data['address_choice'] === 'new' && $request->boolean('save_address')) {
            $this->addressService->create(auth()->id(), ['line' => $shippingAddress]);
        }

        $cart = $this->cartService->currentCart(auth()->id(), $request->session()->getId());

        try {
            $order = $this->orderService->checkout(
                $cart,
                auth()->id(),
                $shippingAddress,
                $data['discount_code'] ?? null,
                $data['redeem_points'] ?? 0,
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()])->withInput();
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order placed!');
    }
}
