<?php

namespace Modules\Product\Events;

use Modules\Order\Events\OrderFullfiled;
use Modules\Product\Warehouse\ProductStockManager;

class DecreaseProductStock
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
