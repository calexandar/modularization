<?php

namespace Modules\Order\Checkout;


use Modules\Payment\PayOrder;
use Modules\Order\Events\OrderStarted;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Console\Scheduling\Event;


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