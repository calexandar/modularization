<?php

namespace Modules\Order\Actions;

use Modules\Payment\PayBuddy;
use Modules\Order\Models\Order;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Modules\Order\Exceptions\PaymentFailedException;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager
    )
    {
        
    }
    public function handle(CartItemCollection $items, PayBuddy $paymentProvider, string $paymentToken, int $userId): Order
    {
        $orderTotalInCents = $items->totalInCents();
        try {
            $charge = $paymentProvider->charge(
            token: $paymentToken,
            amountInCents:    $orderTotalInCents,
            statementDescription: 'Modularization',
        );
        } catch (\RuntimeException) {
            throw PaymentFailedException::dueToInvalidToken();

        }
   
       
        $order = Order::query()->create([
            'payment_id' => $charge['id'],
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'total_in_cents' => $orderTotalInCents,
            'user_id' => $userId
        ]);

   
        foreach ($items->items() as $cartItem) {
            $this->productStockManager->decrement($cartItem->product->id, $cartItem->quantity);

            $order->lines()->create([
                'product_id' => $cartItem->product->id,
                'product_price_in_cents' => $cartItem->product->priceInCents,
                'quantity' => $cartItem->quantity,
            ]);
        }

        $payment = $order->payments()->create([
            'total_in_cents' => $orderTotalInCents,
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'payment_id' => $charge['id'],
            'user_id' => $userId,
            'order_id' => $order->id
        ]);

        return $order;
    }
}