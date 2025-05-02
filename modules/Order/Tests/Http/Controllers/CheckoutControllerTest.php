<?php

namespace Modules\Order\Tests\Http\Controllers;


use Tests\TestCase;
use Database\Factories\UserFactory;
use Modules\Payment\PayBuddy\PayBuddy;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Modules\Product\Database\Factories\ProductFactory;


class CheckoutControllerTest extends TestCase
{
    public function it_succesufly_crates_an_order(): void
    {
        $user = UserFactory::new()->create();

        $products = ProductFactory::new()->count(2)->create(
            new Sequence(
                ['name' => 'Product 1','price_in_cents' => 1000, 'stock' => 10],
                ['name' => 'Product 2','price_in_cents' => 2000, 'stock' => 20],
            )
        );

        $paymentToken = PayBuddy::validToken();
    }
}        