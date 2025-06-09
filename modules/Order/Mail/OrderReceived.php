<?php

namespace Modules\Order\Mail;

class OrderReceived
{
    public function __construct(public float $total)
    {
    }
}