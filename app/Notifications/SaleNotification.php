<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Sale;
use Illuminate\Notifications\Messages\MailMessage;

class SaleNotification extends BaseSystemNotification
{
    public Sale $sale;

    public function __construct(Sale $sale, string $channelType = 'mail')
    {
        parent::__construct('Sale Details - ' . $sale->reference, $channelType);
        $this->sale = $sale;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.sale', ['sale' => $this->sale]);
    }
}
