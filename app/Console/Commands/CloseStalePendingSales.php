<?php

namespace App\Console\Commands;

use App\Models\Sale;
use Illuminate\Console\Command;

class CloseStalePendingSales extends Command
{
    protected $signature = 'sales:close-stale';
    protected $description = 'Cancela vendas pendentes com mais de 7 dias';

    public function handle(): void
    {
        $count = Sale::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(7))
            ->update(['status' => 'cancelled']);

        $this->info("{$count} vendas canceladas por inatividade.");
    }
}