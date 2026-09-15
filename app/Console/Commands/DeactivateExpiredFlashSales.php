<?php

namespace App\Console\Commands;

use App\Models\FlashSale;
use Illuminate\Console\Command;

class DeactivateExpiredFlashSales extends Command
{
    protected $signature = 'flash-sales:deactivate-expired';

    protected $description = 'Deactivate flash sales whose end time has passed';

    public function handle(): int
    {
        $count = FlashSale::query()
            ->where('is_active', true)
            ->where('ends_at', '<', now())
            ->update(['is_active' => false]);

        $this->info("Deactivated {$count} expired flash sale(s).");

        return self::SUCCESS;
    }
}
