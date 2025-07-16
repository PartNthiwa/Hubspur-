<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\MUMBOS\Models\Contribution;


class ContributionApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contribution;
     public string|null $receiptUrl;

    /**
     * Create a new message instance.
     */
   
  public function __construct(Contribution $contribution, ?string $receiptUrl = null)
{
    $this->contribution = $contribution;


    $this->receiptUrl = $contribution->receipt_url
        ? url($contribution->receipt_url)
        : null;
}

    /**
     * Get the message envelope.
     */
   public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Contribution Has Been Approved'
        );
    }
    /**
     * Get the message content definition.
     */
 public function content(): Content
{
    return new Content(
        view: 'emails.contribution-approved',
        with: [
            'contribution' => $this->contribution,
              'receiptUrl'   => $this->receiptUrl,
        ]
    );
}

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
