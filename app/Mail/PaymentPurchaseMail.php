<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentPurchaseMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $invoice, public $pdf)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Purchase Payment Invoice - ' . settings()->company_name)
            ->markdown('emails.payment-purchase-mail')
            ->attachData($this->pdf, 'PaymentPurchase_' . $this->invoice['reference'] . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->invoice);
    }
}
