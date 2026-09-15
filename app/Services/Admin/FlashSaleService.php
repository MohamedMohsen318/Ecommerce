<?php

namespace App\Services\Admin;

use App\Models\FlashSale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FlashSaleService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return FlashSale::withCount('items')->latest()->paginate($perPage);
    }

    public function create(array $data): FlashSale
    {
        return DB::transaction(function () use ($data) {
            $flashSale = FlashSale::create([
                'name' => $data['name'],
                'starts_at' => $data['starts_at'],
                'ends_at' => $data['ends_at'],
                'is_active' => $data['is_active'] ?? false,
            ]);

            $this->syncItems($flashSale, $data['items'] ?? []);

            return $flashSale;
        });
    }

    public function update(FlashSale $flashSale, array $data): FlashSale
    {
        return DB::transaction(function () use ($flashSale, $data) {
            $flashSale->update([
                'name' => $data['name'],
                'starts_at' => $data['starts_at'],
                'ends_at' => $data['ends_at'],
                'is_active' => $data['is_active'] ?? false,
            ]);

            $this->syncItems($flashSale, $data['items'] ?? []);

            return $flashSale;
        });
    }

    public function delete(FlashSale $flashSale): void
    {
        $flashSale->delete();
    }

    /**
     * @param  list<array{item_id?: string, sale_price?: string}>  $items
     */
    protected function syncItems(FlashSale $flashSale, array $items): void
    {
        $sync = [];

        foreach ($items as $row) {
            if (empty($row['item_id']) || ! is_numeric($row['sale_price'] ?? null)) {
                continue;
            }

            $sync[(int) $row['item_id']] = ['sale_price' => $row['sale_price']];
        }

        $flashSale->items()->sync($sync);
    }
}
