<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class QuotationController extends Controller
{
    public function index(): RedirectResponse
    {
        abort_if(Gate::denies('quotation_access'), 403);

        return redirect()->route('quotations.index');
    }

    public function show(Quotation $quotation): RedirectResponse
    {
        abort_if(Gate::denies('quotation_access'), 403);

        return redirect()->route('quotation.edit', ['id' => $quotation->id]);
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        abort_if(Gate::denies('quotation_delete'), 403);

        $quotation->delete();

        // toast('Quotation Deleted!', 'warning');

        return redirect()->route('quotations.index');
    }
}
