<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ProductTelegram extends Notification
{
    use Queueable;

    public $telegramChannel;
    public $productName;
    public $productPrice;

    /**
     * @param mixed $telegramChannel
     * @param mixed $productName
     * @param mixed $productPrice
     *
     * @return void
     */
    public function __construct($telegramChannel, $productName, $productPrice)
    {
        $this->telegramChannel = $telegramChannel;
        $this->productName = $productName;
        $this->productPrice = $productPrice;
    }

    /**
     * @param mixed $notifiable
     *
     * @return array<string>
     */
    public function via($notifiable)
    {
        return ['telegram'];
    }

    /**
     * @param mixed $notifiable
     *
     * @return TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($this->telegramChannel)
            ->content("Check out our new product: {$this->productName} for {$this->productPrice}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'product_name'  => $this->productName,
            'product_price' => $this->productPrice,
        ];
    }
}
