<?php

namespace App\Mail;

use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrackingLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Package $package)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Numéro de suivi de votre colis - ' . $this->package->tracking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tracking-link',
            with: [
                'package' => $this->package,
                'trackingLink' => route('tracking.show', $this->package->id),
                'trackingNumber' => $this->package->tracking_number,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
