<?php

namespace App\Listeners;

use App\Events\SaleCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSaleCreatedNotification
{
    public function handle(SaleCreated $event): void
    {
        logger()->info('Nova venda criada', [
            'sale_id' => $event->sale->id,
            'total' => $event->sale->total,
        ]);
    }
}
