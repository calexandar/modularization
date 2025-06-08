<?php

namespace Modules\Order\Events;

use Modules\Order\Events\OrderFullfiled;

class SendOrderConfirmationEmail
{
    public function handle(OrderFullfiled $event): void
    {
        dd($event);
    }   
}