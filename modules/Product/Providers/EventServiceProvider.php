<?php

namespace Modules\Product\Providers;

use Modules\Order\Events\OrderFullfiled;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Product\Events\DecreaseProductStock;

class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderFullfiled::class => [
            DecreaseProductStock::class,
        ],
    ];
}