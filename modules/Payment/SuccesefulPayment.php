<?php

namespace Modules\Payment;

class SuccesefulPayment
{
    public function __construct(
        public string $id,
        public int $amountInCents,
        public PaymentProvider $provider
    ) {}
}
