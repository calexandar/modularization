<?php

namespace Modules\Order\Tests\Http\Controllers;



use Modules\Payment\PayBuddy;
use Modules\Order\Models\Order;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Modules\Order\Tests\OrderTestCase;
use PHPUnit\Framework\Attributes\Test;
use Modules\Order\Events\OrderFullfiled;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Modules\Product\Database\Factories\ProductFactory;

class CheckoutControllerTest extends OrderTestCase
{
    use DatabaseMigrations;

    #[Test]
    public function it_succesufly_creates_an_order(): void
    {
        Mail::fake();
        Event::fake();
        
        $user = UserFactory::new()->create();

        $products = ProductFactory::new()->count(2)->create(
            new Sequence(
                ['name' => 'Product 1', 'price_in_cents' => 1000, 'stock' => 10],
                ['name' => 'Product 2', 'price_in_cents' => 2000, 'stock' => 20],
            )
        );

        $paymentToken = PayBuddy::validToken();

        $response = $this->actingAs($user)
            ->postJson(route('checkout'), [
                'payment_token' => $paymentToken,
                'products' => [
                    ['id' => $products->first()->id, 'quantity' => 1],
                    ['id' => $products->last()->id, 'quantity' => 1],
                ],
            ]);

        $order = Order::query()->latest('id')->first();

        $response
            ->assertJson([
                'order_url' => $order->url(),
            ])
            ->assertStatus(201);
            
        Event::assertDispatched(OrderFullfiled::class);    

        // Mail::assertSent(OrderReceived::class, function(OrderReceived $mail) use ($user) {
        //     return $mail->hasTo($user->email);
        // });    

        // Order
        $this->assertTrue($order->user->is($user));
        $this->assertEquals(3000, $order->total_in_cents);
        $this->assertEquals('paid', $order->status);
        $this->assertEquals('PayBuddy', $order->payment_gateway);
        $this->assertEquals(36, strlen($order->payment_id));

        // Payment
        $payment = $order->lastPayment;
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals('PayBuddy', $payment->payment_gateway);
        $this->assertEquals(36, strlen($payment->payment_id));
        $this->assertEquals(3000, $payment->total_in_cents);
        $this->assertTrue($payment->user->is($user));

        // Order Lines
        $this->assertCount(2, $order->lines);

        foreach ($products as $product) {
            $orderLine = $order->lines->where('product_id', $product->id)->first();

            $this->assertEquals($product->price_in_cents, $orderLine->product_price_in_cents);
            $this->assertEquals(1, $orderLine->quantity);

        }

        $products = $products->fresh();

        $this->assertEquals(9, $products->first()->stock);
        $this->assertEquals(19, $products->last()->stock);

    }

    #[Test]
    public function it_fails_when_payment_token_is_invalid(): void
    {
        $user = UserFactory::new()->create();
        $product = ProductFactory::new()->create();
        $paymentToken = PayBuddy::invalidToken();

        $response = $this->actingAs($user)
            ->postJson(route('checkout'), [
                'payment_token' => $paymentToken,
                'products' => [
                    ['id' => $product->id, 'quantity' => 1],
                ],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payment_token']);

        $this->assertEquals(0, Order::query()->count());
    }
}
