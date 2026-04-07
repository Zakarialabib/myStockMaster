<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SaleReturnPayment;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReturnNotification extends BaseSystemNotification
{
    public SaleReturnPayment $payment;

    public function __construct(SaleReturnPayment $payment, string $channelType = 'mail')
    {
        parent::__construct('Payment Details - ' . $payment->reference, $channelType);
        $this->payment = $payment;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.payment.return', ['payment' => $this->payment]);
    }
}
