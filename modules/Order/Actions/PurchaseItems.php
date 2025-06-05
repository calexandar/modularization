<?php

namespace Modules\Order\Actions;

use Illuminate\Database\DatabaseManager;
use Modules\Payment\PayBuddy;
use Modules\Order\Models\Order;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Modules\Payment\Actions\CreatePaymentForOrder;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager
    )
    {
        
    }
    public function handle(CartItemCollection $items, PayBuddy $paymentProvider, string $paymentToken, int $userId): Order
    {

        return $this->databaseManager->transaction(function () use ($items, $paymentProvider, $paymentToken, $userId) {
            $order = Order::startForUser($userId);
            $order->addLineitemsFromCartItems($items);
            $order->fulfill();
    
            foreach ($items->items() as $cartItem) {
                $this->productStockManager->decrement($cartItem->product->id, $cartItem->quantity);
            }

            $this->createPaymentForOrder->handle(
                orderId: $order->id, 
                userId: $userId, 
                totalInCents: $items->totalInCents(), 
                payBuddy: $paymentProvider, 
                paymentToken: $paymentToken);

          return $order;
       });

       return $order;

    }
}