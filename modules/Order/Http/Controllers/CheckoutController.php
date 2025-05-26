<?php

namespace Modules\Order\Http\Controllers;

use Modules\Payment\PayBuddy;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request)
    {
        $products = collect($request->input('products'))->map(function (array $productDetails){
            return [
                'product' => Product::find($productDetails['id']),
                'quantity' => $productDetails['quantity']
            ];
        });

        $orderTotalInCents = $products->sum(fn (array $productDetails) =>
            $productDetails['product']->price_in_cents * $productDetails['quantity']
        );

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

   
        foreach ($products as $product) {
            $product['product']->decrement('stock', $product['quantity']);

            $order->lines()->create([
                'product_id' => $product['product']->id,
                'product_price_in_cents' => $product['product']->price_in_cents,
                'quantity' => $product['quantity'],
            ]);
        }
      
        return response()->json([], 201);
    }
  
}