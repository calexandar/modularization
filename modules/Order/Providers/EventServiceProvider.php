<?php

namespace Modules\Order\Providers;

use Modules\Order\Events\OrderFullfiled;
use Modules\Order\Events\SendOrderConfirmationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;


class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderFullfiled::class => [
            SendOrderConfirmationEmail::class,
        ]
    ];
}