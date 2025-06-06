<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\CheckoutController;
use Modules\Order\Http\Controllers\MailcoachTest;
use Modules\Order\Models\Order;

Route::middleware('auth')->group(function () {
    Route::post('checkout', CheckoutController::class)
        ->name('checkout');

    Route::get('orders/{order}', function (Order $order) {
        return $order;
    })->name('orders.show');

});
Route::post('/api/newsletters/sign-up', MailcoachTest::class)->middleware('throttle:3,1');
