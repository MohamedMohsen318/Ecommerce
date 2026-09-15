<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function create(int $userId, array $data): Address
    {
        return DB::transaction(function () use ($userId, $data) {
            if (! empty($data['is_default'])) {
                Address::where('user_id', $userId)->update(['is_default' => false]);
            }

            return Address::create([
                'user_id' => $userId,
                'label' => $data['label'] ?? null,
                'line' => $data['line'],
                'is_default' => $data['is_default'] ?? false,
            ]);
        });
    }

    public function update(Address $address, array $data): Address
    {
        return DB::transaction(function () use ($address, $data) {
            if (! empty($data['is_default'])) {
                Address::where('user_id', $address->user_id)
                    ->whereKeyNot($address->id)
                    ->update(['is_default' => false]);
            }

            $address->update([
                'label' => $data['label'] ?? null,
                'line' => $data['line'],
                'is_default' => $data['is_default'] ?? false,
            ]);

            return $address;
        });
    }

    public function delete(Address $address): void
    {
        $address->delete();
    }
}
