<?php

namespace Modules\Payment;

use Illuminate\Support\Str;

class InMemoryGateway implements PaymentGateway
{
    public function charge(PaymentDetails $paymentDetails): SuccesefulPayment
    {
        return new SuccesefulPayment(
            (string) Str::uuid(),
            $paymentDetails->amountInCents,
            $this->id()
        );
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::IN_MEMORY;
    }
}
