<?php

namespace Modules\Order\Events;

use Modules\Order\DTOs\OrderDto;
use Modules\User\UserDto;

readonly class OrderStarted
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
        public PendingPayment $pendingPayment
    ) {}
}
