<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Order\Actions\PurchaseItems;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;

class CheckoutController extends Controller
{
    public function __construct(
        protected PurchaseItems $purchaseItems
    ) {}

    public function __invoke(CheckoutRequest $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));

        $payBuddy = new PayBuddy;
        $payBuddy->make();

        try {
            $order = $this->purchaseItems->handle(
                $cartItems,
                $payBuddy,
                $request->input('payment_token'),
                $request->user()->id
            );
        } catch (PaymentFailedException) {
            throw ValidationException::withMessages(['
            payment_token' => 'We could not process your payment.']);
        }

        return response()->json([
            'order_url' => $order->url(),
        ], 201);
    }
}
