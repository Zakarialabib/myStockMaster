<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReturn extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $invoice;

    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $pdf)
    {
        $this->invoice = $invoice;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('PAYMENT RECEIPT')
            ->markdown('emails.paymentReturn')
            ->attachData($this->pdf, 'Payment_Return'.$this->invoice['Ref'].'.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->invoice);
    }
}
