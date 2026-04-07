<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ProductTelegram extends Notification
{
    use Queueable;

    /**
     * @param mixed $telegramChannel
     * @param mixed $productName
     * @param mixed $productPrice
     */
    public function __construct(public $telegramChannel, public $productName, public $productPrice)
    {
    }

    /**
     * @param mixed $notifiable
     *
     * @return array<string>
     */
    public function via($notifiable): array
    {
        return ['telegram'];
    }

    /**
     * @param mixed $notifiable
     */
    public function toTelegram($notifiable): \NotificationChannels\Telegram\TelegramMessage
    {
        return TelegramMessage::create()
            ->to($this->telegramChannel)
            ->content(sprintf('Check out our new product: %s for %s', $this->productName, $this->productPrice));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'product_name' => $this->productName,
            'product_price' => $this->productPrice,
        ];
    }
}
