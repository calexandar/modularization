<?php

namespace Modules\Order\Http\Controllers;

use Modules\User\UserDto;
use Modules\Payment\PayBuddy;
use App\Http\Controllers\Controller;
use Modules\Order\DTOs\PendingPayment;
use Modules\Product\CartItemCollection;
use Modules\Order\Actions\PurchaseItems;
use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Order\Exceptions\PaymentFailedException;

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

        $pendingPayment = new PendingPayment($payBuddy, $request->input('payment_token'));
        $userDto = UserDto::fromEloquentModel($request->user());

        try {
            $order = $this->purchaseItems->handle(
                $cartItems,
                $pendingPayment,
                $userDto
            );
        } catch (PaymentFailedException) {
            throw ValidationException::withMessages(['
            payment_token' => 'We could not process your payment.']);
        }

        return response()->json([
            'order_url' => $order->url,
        ], 201);
    }
}
