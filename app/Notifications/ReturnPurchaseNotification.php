<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\PurchaseReturn;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnPurchaseNotification extends BaseSystemNotification
{
    public PurchaseReturn $purchaseReturn;

    public function __construct(PurchaseReturn $purchaseReturn, string $channelType = 'mail')
    {
        parent::__construct('Purchase Return Details - ' . $purchaseReturn->reference, $channelType);
        $this->purchaseReturn = $purchaseReturn;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.purchase-return', ['purchaseReturn' => $this->purchaseReturn]);
    }
}
