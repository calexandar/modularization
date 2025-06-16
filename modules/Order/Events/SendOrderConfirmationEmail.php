<?php

namespace Modules\Order\Events;

use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\OrderReceived;
use Modules\Order\Events\OrderFullfiled;

class SendOrderConfirmationEmail
{
    public function handle(OrderFullfiled $event): void
    {
        Mail::to($event->user->email)->send(new OrderReceived($event->order->localizedTotal));
    }   
}