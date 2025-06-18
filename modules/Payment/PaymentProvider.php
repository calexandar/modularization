<?php

namespace Modules\Payment;

enum PaymentProvider: string
{
    case IN_MEMORY = 'in_memory';
    case PAYBUDDY = 'paybuddy';
}