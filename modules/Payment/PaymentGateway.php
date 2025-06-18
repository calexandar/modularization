<?php

namespace Modules\Payment;

interface PaymentGateway
{
    public function charge(PaymentDetails $details): SuccesefulPayment;

    public function id(): PaymentProvider;
}