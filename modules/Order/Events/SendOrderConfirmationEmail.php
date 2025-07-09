<?php

namespace Modules\Order\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\OrderReceived;

class SendOrderConfirmationEmail implements ShouldQueue
{
    public function handle(OrderFullfiled $event): void
    {
        Mail::to($event->user->email)->send(new OrderReceived($event->order->localizedTotal));
    }
}
