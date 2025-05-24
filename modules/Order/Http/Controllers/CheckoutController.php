<?php

namespace Modules\Order\Http\Controllers;

use Modules\Payment\PayBuddy;
use Modules\Product\Models\Product;
use App\Http\Controllers\Controller;
use Modules\Order\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request)
    {
        $products = array_map(function (array $productDetails) {
            return [
                'product' => Product::find($productDetails['id']),
                'quantity' => $productDetails['quantity']
            ];
        }, array: $request->input('products'));
        dd($products);
        $payBuddy = PayBuddy::validToken();
    }
  
}