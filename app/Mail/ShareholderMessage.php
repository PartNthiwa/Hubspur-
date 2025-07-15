<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShareholderMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $messageBody;
    public $subjectLine;
    public $attachments;

    /**
     * Create a new message instance.
     */
     public function __construct($subjectLine, $messageBody, $attachments = [])
    {
        $this->subjectLine = $subjectLine;
        $this->messageBody = $messageBody;
        $this->attachments = $attachments;
    }
    /**
     * Get the message envelope.
     */
 
    /**
     * Get the message content definition.
     */
   public function build()
    {
        $email = $this->subject($this->subjectLine)
                      ->view('emails.shareholder-message')
                      ->with([
                          'messageBody' => $this->messageBody,
                      ]);

        foreach ($this->attachments as $path) {
            $email->attach($path);
        }

        return $email;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
 
}
