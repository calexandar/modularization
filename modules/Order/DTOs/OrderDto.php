<?php

namespace Modules\Order\DTOs;

use Modules\Order\Models\Order;

readonly class OrderDto
{
    public function __construct(
        public int $id,
        public int $totalInCents,
        public string $localizedTotal,
        public array $lines
    )
    {
        
    }

    public static function fromEloquentModel(Order $order): self
    {
        return new self(
            id: $order->id,
            totalInCents: $order->totalInCents,
            localizedTotal: $order->localizedTotal
        );
    }
}