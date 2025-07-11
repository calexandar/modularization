<?php

namespace Modules\Payment;

use Modules\Order\Mail\OrderReceived;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Order\Exceptions\PaymentFailedException;

class PayOrder implements ShouldQueue
{
    public function __construct(
        private CreatePaymentForOrder $createPaymentForOrder,
        protected Dispatcher $events,
    ) {}

    public function handle(OrderReceived $event): void
    {
        try {
            $this->createPaymentForOrder->handle(
                $event->order->id,
                $event->user->id,
                $event->order->totalInCents,
                $event->order->pendingPayment->provider,
                $event->order->pendingPayment->token
            );
        } catch (PaymentFailedException $exception) {
            $this->events->dispatch(
                new PaymentFailed($event->order, $event->user, $exception->getMessage())
            );
            throw $exception;
        }

        $this->events->dispatch(
            new PaymentSuceesful($event->order, $event->user)
        );
    }
}
