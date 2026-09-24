<?php

namespace App\Jobs;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMonthlySalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $companyId) {}

    public function handle(): void
    {
        $total = Sale::where('company_id', $this->companyId)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        logger()->info("Relatório mensal gerado", [
            'company_id' => $this->companyId,
            'total' => $total,
        ]);

    }
}
