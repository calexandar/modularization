<?php

namespace Modules\Order\Events;

use Modules\User\UserDto;
use Modules\Order\DTOs\OrderDto;

readonly class OrderFullfiled
{
    /**
     * Create a new event instance.
     *
     * @param OrderDto $order
     * @param UserDto $user
     */
    public function __construct(
     public OrderDto $order,
     public UserDto $user
    )
    {
    }
}                                                                                                                                                               