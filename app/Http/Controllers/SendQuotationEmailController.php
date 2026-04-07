<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Notifications\QuotationNotification;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class SendQuotationEmailController extends Controller
{
    public function __invoke(Quotation $quotation): RedirectResponse
    {
        try {
            Notification::route('mail', $quotation->customer->email)
                ->notify(new QuotationNotification($quotation));

            $quotation->update([
                'status' => 'Sent',
            ]);

            // toast('Sent On "'.$quotation->customer->email.'"!', 'success');
        } catch (Exception $exception) {
            report($exception);
            // toast('Something Went Wrong!', 'error');
        }

        return back();
    }
}
