<?php

namespace Modules\Order\Http\Controllers;

use Modules\Payment\PayBuddy;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use App\Http\Controllers\Controller;
use Modules\Product\Models\CartItem;
use Modules\Product\CartItemCollection;
use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Product\Warehouse\ProductStockManager;

class CheckoutController extends Controller
{
    public function __construct(
        protected ProductStockManager $productStockManager
    )
    {
        
    }
    public function __invoke(CheckoutRequest $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));
   

        $orderTotalInCents = $cartItems->totalInCents();

        $payBuddy = new PayBuddy;
        $payBuddy->make();
        try {
            $charge = $payBuddy->charge(
            token: $request->input('payment_token'),
            amountInCents:    $orderTotalInCents,
            statementDescription: 'Modularization',
        );
        } catch (\RuntimeException $e) {
            throw ValidationException::withMessages([
                'payment_token' => 'We couldn\'t process your payment.',
            ]);
        }
   
       
        $order = Order::query()->create([
            'payment_id' => $charge['id'],
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'total_in_cents' => $orderTotalInCents,
            'user_id' => $request->user()->id,
        ]);

   
        foreach ($cartItems->items() as $cartItem) {
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
            'user_id' => $request->user()->id,
            'order_id' => $order->id
        ]);
      
        return response()->json([], 201);
    }
  
}