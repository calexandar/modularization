<?php

namespace Modules\Order\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Modules\Order\DTOs\OrderDto;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OrderDto $order
        ) {}

    public function envelope(): Envelope
    {
        return new  Envelope(
            subject: 'Order Received',
        );
    }

    public function content()
    {
        return new Content(
            view: 'order::emails.order_received',
        );
    }
}
