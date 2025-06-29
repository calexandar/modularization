<?php

namespace Modules\Order\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Events\OrderFullfiled;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Modules\User\UserDto;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrderInterface $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events
    ) {}

    public function handle(CartItemCollection $items, PendingPayment $pendingPayment, UserDto $user): OrderDto
    {

        $order = $this->databaseManager->transaction(function () use ($user, $items) {
            $order = Order::startForUser($user->id);
            $order->addLineitemsFromCartItems($items);
            $order->start();

            return OrderDto::fromEloquentModel($order);
        });

        $this->events->dispatch(
            new OrderStarted(
                order: $order,
                user: $user,
                pendingPayment: $pendingPayment
            ));

        return $order;

    }
}
