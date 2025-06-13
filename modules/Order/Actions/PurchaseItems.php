<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;
use Modules\Product\CartItemCollection;
use Illuminate\Database\DatabaseManager;
use Modules\Order\Events\OrderFullfiled;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Order\DTOs\PendingPayment;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Product\Warehouse\ProductStockManager;
use Modules\User\UserDto;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events
    ) {}

    public function handle(CartItemCollection $items, PendingPayment $pendingPayment, UserDto $user): Order
    {

        $order = $this->databaseManager->transaction(function () use ($user, $items) {
            $order = Order::startForUser($user->id);
            $order->addLineitemsFromCartItems($items);
            $order->fulfill();

            $this->createPaymentForOrder->handle(
                orderId: $order->id,
                userId: $user->id,
                totalInCents: $items->totalInCents(),
                payBuddy: $pendingPayment->provider,
                paymentToken: $pendingPayment->paymentToken);

            return $order;
        });

        $this->events->dispatch(
            new OrderFullfiled(
                orderId: $order->id,
                totalInCents: $order->totalInCents,
                localizedTotal: $order->localizedTotal(),
                cartItems: $items,
                userId: $userId,
                userEmail: $userEmail
            )
        );

        return $order;

    }
}
