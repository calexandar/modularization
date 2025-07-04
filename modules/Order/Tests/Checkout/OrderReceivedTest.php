<?php

namespace Modules\Order\Checkout;

use Modules\Order\DTOs\OrderDto;
use Modules\Order\Mail\OrderReceived;
use PHPUnit\Framework\Attributes\Test;

class OrderReceivedTest extends \Tests\TestCase
{
    #[Test]
    public function it_renders_the_mailable(): void
    {
        $orderDto = new OrderDto(
            id: 1,
            totalInCents: 1000,
            localizedTotal: '1000',
            url: route('orders.show', 1),
            lines: []
        );
        $orderReceived = new OrderReceived($orderDto);

        $this->assertIsString($orderReceived->render());
    }
}
