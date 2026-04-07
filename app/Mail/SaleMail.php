<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $sale, public $pdf)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sale Details - ' . settings()->company_name)
            ->markdown('emails.saleMail')
            ->attachData($this->pdf, 'Sale_' . $this->sale->reference . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->sale);
    }
}
