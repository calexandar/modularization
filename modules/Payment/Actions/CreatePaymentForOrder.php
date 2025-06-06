<?php

namespace Modules\Payment\Actions;

use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Payment\PayBuddy;
use Modules\Payment\Payment;

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
        PayBuddy $payBuddy,
        string $paymentToken
    ): Payment {
        try {
            $charge = $payBuddy->charge(
                token: $paymentToken,
                amountInCents: $totalInCents,
                statementDescription: 'Modularization',
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
