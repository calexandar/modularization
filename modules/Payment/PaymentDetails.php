<?php

namespace Modules\Payment;

readonly class PaymentDetails
{
    public function __construct(
        public string $paymentToken,
        public int $amountInCents,
        public string $statementDescription
    )
    {
        
    }
}