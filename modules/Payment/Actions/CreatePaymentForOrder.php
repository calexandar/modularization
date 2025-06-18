<?php

namespace Modules\Payment\Actions;

use Modules\Payment\Payment;
use Modules\Payment\PaymentDetails;
use Modules\Payment\PaymentGateway;
use Modules\Order\Exceptions\PaymentFailedException;

class CreatePaymentForOrder
{
    /**
     * Handles the payment process for a given order.
     *
     * @return Payment The payment record created for the order.
     *
     * @throws PaymentFailedException If the payment token is invalid.
     */
    public function handle(
        int $orderId,
        int $userId,
        int $totalInCents,
        PaymentGateway $paymentGateway,
        string $paymentToken
    ): Payment {
        try {
            $charge = $paymentGateway->charge(
               new PaymentDetails(
                $paymentToken, 
                $totalInCents, 
                'Modularization'
               )
            );
        } catch (\RuntimeException) {
            throw PaymentFailedException::dueToInvalidToken();
        }

        return Payment::query()->create([
            'total_in_cents' => $totalInCents,
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'payment_id' => $charge['id'],
            'user_id' => $userId,
            'order_id' => $orderId,
        ]);

    }
}
