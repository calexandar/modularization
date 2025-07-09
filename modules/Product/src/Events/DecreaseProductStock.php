<?php

namespace Modules\Product\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\OrderFullfiled;
use Modules\Product\Warehouse\ProductStockManager;

class DecreaseProductStock implements ShouldQueue
{
    public function __construct(
        protected ProductStockManager $productStockManager
    ) {}

    public function handle(OrderFullfiled $event): void
    {
        foreach ($event->order->lines as $line) {
            $this->productStockManager->decrement($line->product_id, $line->quantity);
        }

    }
}
