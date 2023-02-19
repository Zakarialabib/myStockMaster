<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReturnSaleMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $returnSaleMail;

    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($returnSaleMail, $pdf)
    {
        $this->returnSaleMail = $returnSaleMail;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Return Sale Details - '.settings()->company_name)
            ->markdown('emails.returnSaleMail')
            ->attachData($this->pdf, 'Return_'.$this->returnSaleMail['reference'].'.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->returnSaleMail);
    }
}
