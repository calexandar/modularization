<?php

namespace Modules\Order\DTOs;

use Modules\Order\Models\Order;

readonly class OrderDto
{
    public function __construct(
        public int $id,
        public int $totalInCents,
        public string $localizedTotal,
        public string $url,
        public array $lines
    )
    {
        
    }

    public static function fromEloquentModel(Order $order): self
    {
        return new self(
            id: $order->id,
            totalInCents: $order->totalInCents(),
            localizedTotal: $order->localizedTotal(),
            url: $order->url(),
            lines: OrderLineDto::fromEloquentCollection($order->lines),
        );
    }
}