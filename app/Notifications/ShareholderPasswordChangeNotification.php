<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;


class ShareholderPasswordChangeNotification extends Notification
{
       protected $token;
    protected $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Construct the password reset URL
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ], false));

        Log::info('Generated reset URL: ' . $resetUrl);

        return (new MailMessage)
            ->subject('Reset Your MUMBO Shareholder Password')
            ->greeting('Hello ' . ($notifiable->first_name ?? 'Shareholder') . ',')
            ->line('We received a request to reset your shareholder account password.')
            ->action('Reset Password', $resetUrl)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request this, no further action is needed.')
            ->salutation('– MUMBO Kenya Diaspora Investments');
    }

}
