<?php

namespace Modules\Order\Mail;

class OrderReceived extends \Illuminate\Mail\Mailable
{
    public function __construct(public float $total) {}
}
