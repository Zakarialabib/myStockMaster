<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class QuotationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('quotation_access'), 403);

        return view('admin.quotation.index');
    }

    public function show(Quotation $quotation)
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $customer = Customer::findOrFail($quotation->customer_id);

        return view('admin.quotation.show', compact('quotation', 'customer'));
    }

    public function destroy(Quotation $quotation)
    {
        abort_if(Gate::denies('quotation_delete'), 403);

        $quotation->delete();

        // toast('Quotation Deleted!', 'warning');

        return redirect()->route('quotations.index');
    }
}
