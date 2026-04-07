<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Purchase;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseNotification extends BaseSystemNotification
{
    public Purchase $purchase;

    public function __construct(Purchase $purchase, string $channelType = 'mail')
    {
        parent::__construct('Purchase Details - ' . $purchase->reference, $channelType);
        $this->purchase = $purchase;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.purchase', ['purchase' => $this->purchase]);
    }
}
