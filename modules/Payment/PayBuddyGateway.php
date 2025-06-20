<?php

namespace Modules\Payment;

use Modules\Order\Exceptions\PaymentFailedException;

class PayBuddyGateway implements PaymentGateway
{
    public function __construct(
        private PayBuddy $payBuddy
    ) {}

    public function charge(PaymentDetails $paymentDetails): SuccesefulPayment
    {
        try {
            $charge = $this->payBuddy->charge(
                token: $paymentDetails->paymentToken,
                amountInCents: $paymentDetails->amountInCents,
                statementDescription: $paymentDetails->statementDescription,
            );
        } catch (\RuntimeException $exception) {
            throw new PaymentFailedException(
                $exception->getMessage()
            );
        }

        return new SuccesefulPayment(
            $charge['id'],
            $charge['amountInCents'],
            $this->id()
        );
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::PAYBUDDY;
    }
}
