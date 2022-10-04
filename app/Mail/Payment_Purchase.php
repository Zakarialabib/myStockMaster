<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Payment_Purchase extends Mailable
{
    use Queueable, SerializesModels;

    public $facture;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($facture, $pdf)
    {
        $this->facture = $facture;
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
            ->markdown('emails.paymentPurchase')
            ->attachData($this->pdf, 'Payment_Purchase_' . $this->facture['Ref'] . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->facture);
    }
}
