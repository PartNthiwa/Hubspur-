<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ShareholderInvitationNotification extends Notification
{
    use Queueable;

    /** @var string */
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail message.
     */
    public function toMail($notifiable): MailMessage
    {
        // Build the URL using your Bagisto route name:
        $resetUrl = url(
            route('shop.shareholders.password.reset.form', $this->token, false)
            . '?email=' . urlencode($notifiable->email)
        );

        return (new MailMessage)
            ->subject('You’re Invited to MUMBO!')
            ->greeting("Hello {$notifiable->first_name} {$notifiable->last_name},")
            ->line('We’re excited to have you on board as a shareholder.')
            ->line('To get started, please click the button below to set your password and complete your account setup:')
            ->action('Set Up My Account', $resetUrl)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not expect this email, simply ignore it or contact support at support@mumbodiaspora.org.')
            ->salutation('Warm regards, MUMBO Diaspora Team');
    }
}
