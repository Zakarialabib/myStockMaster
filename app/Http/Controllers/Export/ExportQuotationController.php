<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Http\Response;

class ExportQuotationController extends Controller
{
    public function __invoke(int|string $id): Response
    {
        $quotation = Quotation::query()->where('id', $id)->firstOrFail();
        $customer = Customer::query()->where('id', $quotation->customer->id)->firstOrFail();

        $data = [
            'quotation' => $quotation,
            'customer' => $customer,
        ];

        return response()->view('admin.quotation.print', $data);
    }
}
