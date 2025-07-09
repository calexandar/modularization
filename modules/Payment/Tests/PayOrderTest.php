<?php

namespace Modules\Payment\Tests;

use Tests\TestCase;
use Modules\Payment\PayOrder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PayOrderTest extends TestCase
{
    use DatabaseMigrations;

    #[Test]
    public function it_is_queued()
    {
        $this->assertInstanceOf(ShouldQueue::class, app(PayOrder::class));
    }
}