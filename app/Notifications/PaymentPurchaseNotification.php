<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\PurchasePayment;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentPurchaseNotification extends BaseSystemNotification
{
    public PurchasePayment $payment;

    public function __construct(PurchasePayment $payment, string $channelType = 'mail')
    {
        parent::__construct('Payment Details - ' . $payment->reference, $channelType);
        $this->payment = $payment;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.payment.purchase', ['payment' => $this->payment]);
    }
}
