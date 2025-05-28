<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
// User Notification Classes
class OrderSent extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;

    public function __construct(
        $user,
        $order
    ) {
        $this->user = $user;
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order #' . $this->order->order_id . ' Sent',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.user.order-sent',
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
