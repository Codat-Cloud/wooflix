<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $statusMessage;

    public function __construct(Order $order, $statusMessage)
    {
        $this->order = $order;
        $this->statusMessage = $statusMessage;
    }

        public function build()
    {
        return $this
            ->subject(
                'Order ' .
                ucfirst($this->order->status) .
                ' - ' .
                $this->order->order_number
            )
            ->view('emails.order-status');
    }

    // /**
    //  * Get the message envelope.
    //  */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Order Status Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
