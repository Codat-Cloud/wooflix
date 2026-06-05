<?php

namespace App\Mail;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class AbandonedCartRecover extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $cartItems;
    public $settings;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $cartItems)
    {
        $this->user = $user;
        $this->cartItems = $cartItems;
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
            subject: '🐾 Did you forget something? Your pet\'s favorites are waiting! 🛒',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-cart',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
