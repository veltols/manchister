<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification
{
    use Queueable;

    private string $otpCode;
    private int $expiryMinutes;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otpCode, int $expiryMinutes = 10)
    {
        $this->otpCode = $otpCode;
        $this->expiryMinutes = $expiryMinutes;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'IQC Sense');
        $logoUrl = asset('images/logo.png');
        
        return (new MailMessage)
            ->subject('Your Login Verification Code - ' . $appName)
            ->view('emails.otp', [
                'otp' => $this->otpCode,
                'appName' => $appName,
                'logoUrl' => $logoUrl,
                'expiryMinutes' => $this->expiryMinutes
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp_requested_at' => now(),
        ];
    }
}
