<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class WelcomeNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Our Application!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for registering with our application. We are excited to have you on board!')
            ->action('Get Started', url('/'))
            ->line('If you have any questions, feel free to contact our support team.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message'   =>  'Welcome to Our Application! Thank you for registering with us.',
            'type'      =>  'welcome',
        ];
    }
}
