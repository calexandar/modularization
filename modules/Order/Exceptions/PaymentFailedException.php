<?php

namespace Modules\Order\Exceptions;


class PaymentFailedException extends \RuntimeException
{
    public static function dueToInvalidToken(): PaymentFailedException
    {
        return new self('Payment failed due to invalid token.');
    }
}