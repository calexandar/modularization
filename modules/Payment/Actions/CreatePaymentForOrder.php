<?php

namespace Modules\Payment\Actions;

use Modules\Payment\Payment;
use Modules\Payment\PayBuddy;
use Modules\Order\Exceptions\PaymentFailedException;

class CreatePaymentForOrder
{
    public function handle(
        int $orderId,
        int $userId,
        int $totalInCents,
        PayBuddy $payBuddy,
        string $paymentToken
    ): Payment 
    {
        try {
            $charge = $payBuddy->charge(
            token: $paymentToken,
            amountInCents:    $totalInCents,
            statementDescription: 'Modularization',
        );
        } catch (\RuntimeException) {
            throw PaymentFailedException::dueToInvalidToken();

        }

        return  Payment::query()->create([
            'total_in_cents' => $totalInCents,
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'payment_id' => $charge['id'],
            'user_id' => $userId,
            'order_id' => $orderId
        ]);
   
    }
}