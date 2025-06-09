<?php

namespace Modules\Order\Events;

use Illuminate\Support\Facades\Mail;
use Modules\Order\Events\OrderFullfiled;

class SendOrderConfirmationEmail
{
    public function handle(OrderFullfiled $event): void
    {
        Mail::to($event->userEmail)->send(new OrderReceived($event->localizedTotal));
    }   
}