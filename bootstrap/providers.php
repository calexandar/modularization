<?php


return [
    App\Providers\AppServiceProvider::class,
    Modules\Order\Providers\OrderServiceProvider::class,
    Modules\Product\src\Providers\ProductServiceProvider::class,
    Modules\Shipment\Providers\ShipmentServiceProvider::class,
    Modules\Payment\Infrastructure\Providers\PaymentServiceProvider::class,
];
