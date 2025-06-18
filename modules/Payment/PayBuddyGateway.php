<?php       

namespace Modules\Payment;

class PayBuddyGateway implements PaymentGateway
{

    public function __construct(
        private PayBuddy $payBuddy
    )
    {
        
    }
    public function charge(PaymentDetails $paymentDetails): SuccesefulPayment
    {
        $charge = $this->payBuddy->charge(
            token: $paymentDetails->paymentToken,
            amountInCents: $paymentDetails->amountInCents,
            statementDescription: $paymentDetails->statementDescription,
        );

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