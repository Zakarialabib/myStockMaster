<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReturnMail extends Mailable
{
    use Queueable, SerializesModels;

    public $Return_Mail;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($Return_Mail, $pdf)
    {
        $this->Return_Mail = $Return_Mail;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->subject('Return Details')
            ->markdown('emails.ReturnMail')
            ->attachData($this->pdf, 'Return_' . $this->Return_Mail['Ref'] . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->Return_Mail);
    }
}
