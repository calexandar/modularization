<?php

namespace Modules\Order\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Events\OrderFullfiled;
use Modules\Order\Events\SendOrderConfirmationEmail;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderFullfiled::class => [
            SendOrderConfirmationEmail::class,
        ],
    ];
}
