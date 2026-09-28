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
    public function __construct(private CartService $cartService, private OrderService $orderService, private AddressService $addressService) {}

    public function create(Request $request): View
    {
        $user = auth()->user();

        $cart = $this->cartService->currentCart($user->id, $request->session()->getId());

        $cart->load('items.item.translations', 'items.item.flashSales', 'items.variant.values.type');

        return view('checkout.create', [
            'cart' => $cart,
            'addresses' => $user->addresses()->latest()->get(),
            'pointsBalance' => $user->loyaltyPointsBalance(),
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

        $userId = auth()->id();
        $isNewAddress = $data['address_choice'] === 'new';

        if ($isNewAddress) {
            $shippingAddress = $data['shipping_address'];
        } else {
            $shippingAddress = Address::query()
                ->where('user_id', $userId)
                ->find($data['address_choice'])
                ?->line;
        }

        if (! $shippingAddress) {
            return back()
                ->withErrors(['shipping_address' => 'Please choose or enter a shipping address.'])
                ->withInput();
        }

        if ($isNewAddress && $request->boolean('save_address')) {
            $this->addressService->create($userId, ['line' => $shippingAddress]);
        }

        $cart = $this->cartService->currentCart($userId, $request->session()->getId());

        try {
            $order = $this->orderService->checkout(
                $cart,
                $userId,
                $shippingAddress,
                $data['discount_code'] ?? null,
                $data['redeem_points'] ?? 0
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()])->withInput();
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order placed!');
    }
}
