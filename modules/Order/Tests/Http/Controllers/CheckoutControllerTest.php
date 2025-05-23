<?php

namespace Modules\Order\Tests\Http\Controllers;

use Modules\Order\Models\Order;
use Database\Factories\UserFactory;
use Modules\Order\Tests\OrderTestCase;
use Modules\Payment\PayBuddy;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Modules\Product\Database\Factories\ProductFactory;


class CheckoutControllerTest extends OrderTestCase
{
    use DatabaseMigrations;
    #[Test]
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

        $response = $this->actingAs($user)
            ->post(route('order::checkout'), [
                'payment_token' => $paymentToken,
                'products' => [
                    ['id' => $products->first()->id,'quantity' => 1],
                    ['id' => $products->last()->id,'quantity' => 1],
                ],
            ]);
            
            $response->assertStatus(201);

            $order = Order::query()->latest('id')->first();

            //Order
            $this->assertTrue( $order->user->is($user) );
            $this->assertEquals(3000, $order->total_in_cents);
            $this->assertEquals('paid', $order->status);
            $this->assertEquals('PayBuddy', $order->payment_gateway);
            $this->assertEquals(36, strlen($order->payment_id));

            //Order Lines
            $this->assertCount(2, $order->lines);

            foreach ($products as $product) {
                $orderLine = $order->lines->where('product_id', $product->id)->first();

                $this->assertEquals($product->price_in_cents, $orderLine->price_in_cents);
                $this->assertEquals(1, $orderLine->quantity);  

            }

             //Event::assertDispatched(OrderStarted::class);

        }

    }