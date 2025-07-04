<?php

namespace Modules\Order\Checkout;

use Illuminate\Console\Scheduling\Event;
use Modules\Order\Events\OrderStarted;
use Modules\Payment\PayOrder;
use PHPUnit\Framework\Attributes\Test;

class OrderStartedTest extends \Tests\TestCase
{
    #[Test]
    public function it_has_listeners(): void
    {
        Event::fake();

        Event::assertListening(OrderStarted::class, SendOrderConfirmationEmail::class);
        Event::assertListening(OrderStarted::class, PayOrder::class);
    }
}
