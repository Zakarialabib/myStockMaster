<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\QuotationMail;
use App\Models\Quotation;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendQuotationEmailController extends Controller
{
    #[Get('/admin/quotation/mail/{quotation}', name: 'quotation.email')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(Quotation $quotation): RedirectResponse
    {
        try {
            Mail::to($quotation->customer->email)->send(new QuotationMail($quotation));

            $quotation->update([
                'status' => 'Sent',
            ]);

            // toast('Sent On "'.$quotation->customer->email.'"!', 'success');
        } catch (Exception $exception) {
            Log::error($exception);
            // toast('Something Went Wrong!', 'error');
        }

        return back();
    }
}
