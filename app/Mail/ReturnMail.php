<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReturnMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $returnMail;

    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($returnMail, $pdf)
    {
        $this->returnMail = $returnMail;
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
            ->markdown('emails.returnMail')
            ->attachData($this->pdf, 'Return_'.$this->returnMail['reference'].'.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->returnMail);
    }
}
