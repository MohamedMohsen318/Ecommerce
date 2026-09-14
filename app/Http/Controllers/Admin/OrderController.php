<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private OrderStatusService $orderStatusService) {}

    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::with('user')->latest()->paginate(15),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items', 'user', 'discount', 'statusHistory.admin'),
            'nextStatuses' => $this->orderStatusService->allowedTransitions($order),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', new Enum(OrderStatus::class)],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->orderStatusService->transition(
                $order,
                OrderStatus::from($data['status']),
                auth('admins')->user(),
                $data['note'] ?? null,
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', 'Order status updated.');
    }
}
