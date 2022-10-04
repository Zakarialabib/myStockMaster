<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($purchase, $pdf)
    {
        $this->purchase = $purchase;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return
        $this->subject('Purchase Details')
            ->markdown('emails.purchase')
            ->attachData($this->pdf, 'Purchase_' . $this->purchase['Ref'] . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->purchase);
    }
}
