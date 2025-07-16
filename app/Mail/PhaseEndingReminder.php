<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\MUMBOS\Models\Phase;
use Webkul\MUMBOS\Models\Contribution;

class PhaseEndingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $phase;
    public $daysLeft;
    public $shareholderName;
    public $contribution;

    public function __construct(Phase $phase, int $daysLeft ,Contribution $contribution)
    {
        $this->phase = $phase;
        $this->daysLeft = $daysLeft;
         $this->shareholderName = $contribution->shareholder->customer->first_name ?? 'Shareholder';
    }

    public function build()
    {
        return $this->subject("Phase '{$this->phase->name}' ends in {$this->daysLeft} day(s)")
                    ->view('emails.phase-ending-reminder');
    }
}
