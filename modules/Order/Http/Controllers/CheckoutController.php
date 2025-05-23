<?php

namespace Modules\Order\Http\Controllers;

use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Payment\PayBuddy\PayBuddy;

class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request)
    {
        $products = array_map(function (array $productDetails) {
            dd($productDetails);
        }, array: $request->input('products'));
        $payBuddy = PayBuddy::validToken();
    }
  
}