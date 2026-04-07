<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SalePayment;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentSaleNotification extends BaseSystemNotification
{
    public SalePayment $payment;

    public function __construct(SalePayment $payment, string $channelType = 'mail')
    {
        parent::__construct('Payment Details - ' . $payment->reference, $channelType);
        $this->payment = $payment;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.payment.sale', ['payment' => $this->payment]);
    }
}
