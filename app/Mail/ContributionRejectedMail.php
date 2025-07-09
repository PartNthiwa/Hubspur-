<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\MUMBOS\Models\Contribution;

class ContributionRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Contribution $contribution;

    public function __construct(Contribution $contribution)
    {
        $this->contribution = $contribution;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Contribution Was Rejected',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contribution-rejected',
            with: [
                'contribution' => $this->contribution,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}