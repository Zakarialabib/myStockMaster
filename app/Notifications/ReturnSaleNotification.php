<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SaleReturn;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnSaleNotification extends BaseSystemNotification
{
    public SaleReturn $saleReturn;

    public function __construct(SaleReturn $saleReturn, string $channelType = 'mail')
    {
        parent::__construct('Sale Return Details - ' . $saleReturn->reference, $channelType);
        $this->saleReturn = $saleReturn;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.sale-return', ['saleReturn' => $this->saleReturn]);
    }
}
