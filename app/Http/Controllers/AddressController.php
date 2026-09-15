<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function __construct(private AddressService $addressService) {}

    public function index(): View
    {
        return view('addresses.index', [
            'addresses' => auth()->user()->addresses()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'line' => ['required', 'string', 'max:500'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        $this->addressService->create(auth()->id(), $data);

        return back()->with('success', 'Address saved.');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'line' => ['required', 'string', 'max:500'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        $this->addressService->update($address, $data);

        return back()->with('success', 'Address updated.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $this->authorize('delete', $address);

        $this->addressService->delete($address);

        return back()->with('success', 'Address removed.');
    }
}
