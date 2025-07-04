<?php

declare(strict_types=1);

namespace Modules\Payment;

use Modules\Order\DTOs\OrderDto;
use Modules\User\UserDto;

readonly class PaymentFailed
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
        public string $reason,
    ) {}
}
