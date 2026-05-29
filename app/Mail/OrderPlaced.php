<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $settings;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        // Explicitly eager-load the relations to ensure line items can pull description data
        $this->order = $order->load('items.product');

        // Pull cached site settings for branding/logo parameters
        $this->settings = Cache::rememberForever('site_settings_all', function () {
            return SiteSetting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🐾 Order Confirmed! #' . $this->order->order_number . ' - Wooflix',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-placed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
