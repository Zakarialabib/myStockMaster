<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BaseSystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $subjectTitle;

    public string $channelType;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $subjectTitle, string $channelType = 'mail')
    {
        $this->subjectTitle = $subjectTitle;
        $this->channelType = $channelType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Dynamically read from settings or default to provided channel + database
        return [$this->channelType, 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'subject' => $this->subjectTitle,
            'channel' => $this->channelType,
            'status' => 'sent',
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
