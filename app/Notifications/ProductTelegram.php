<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ProductTelegram extends Notification
{
    use Queueable;

    public function __construct($telegramChannel, $productName, $productPrice)
    {
        $this->telegramChannel = $telegramChannel;
        $this->productName = $productName;
        $this->productPrice = $productPrice;
    }

    public function via($notifiable) 
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable) 
    {
        return TelegramMessage::create()
        ->to($this->telegramChannel)
        ->content("Check out our new product: $this->productName for $$this->productPrice");
    
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'product_name' => $this->productName,
            'product_price' => $this->productPrice
        ];
    }
}
