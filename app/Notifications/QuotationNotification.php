<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Notifications\Messages\MailMessage;

class QuotationNotification extends BaseSystemNotification
{
    public Quotation $quotation;

    public function __construct(Quotation $quotation, string $channelType = 'mail')
    {
        parent::__construct('Quotation Details - ' . $quotation->reference, $channelType);
        $this->quotation = $quotation;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.quotation', ['quotation' => $this->quotation]);
    }
}
