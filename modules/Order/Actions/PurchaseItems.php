<?php

namespace Modules\Order\Actions;

use Modules\Payment\PayBuddy;
use Modules\Order\Models\Order;
use Illuminate\Support\Facades\Mail;
use Modules\Product\CartItemCollection;
use Illuminate\Database\DatabaseManager;
use Modules\Order\Events\OrderFullfiled;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Product\Warehouse\ProductStockManager;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events
    ) {}

    public function handle(CartItemCollection $items, PayBuddy $paymentProvider, string $paymentToken, int $userId, string $userEmail): Order
    {

        return $this->databaseManager->transaction(function () use ($items, $paymentProvider, $paymentToken, $userId) {
            $order = Order::startForUser($userId);
            $order->addLineitemsFromCartItems($items);
            $order->fulfill();

            $this->createPaymentForOrder->handle(
                orderId: $order->id,
                userId: $userId,
                totalInCents: $items->totalInCents(),
                payBuddy: $paymentProvider,
                paymentToken: $paymentToken);

            return $order;
        });

        $this->events->dispatch(
            new OrderFullfiled(
                orderId: $order->id,
                totalInCents: $order->totalInCents,
                localizedTotal: $order->localizedTotal(),
                userId: $userId,
                userEmail: $userEmail
            )
        );

        return $order;

    }
}
