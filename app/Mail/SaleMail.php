<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sale, $pdf)
    {
        $this->sale = $sale;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sale Details')
            ->markdown('emails.sale')
            ->attachData($this->pdf, 'Sale_' . $this->sale['Ref'] . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->sale);
    }
}
