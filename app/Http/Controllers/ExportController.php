<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\Supplier;
use PDF;

class ExportController extends Controller
{
    public function salePos($id)
    {
        $sale = Sale::where('id', $id)->firstOrFail();

        $data = [
            'sale' => $sale,
        ];

        $pdf = PDF::loadView('admin.sale.print-pos', $data, [], [
            'format' => 'a5',
        ]);

        return $pdf->stream(__('Sale').$sale->reference.'.pdf');
    }

    public function sale($id)
    {
        $sale = Sale::where('id', $id)->firstOrFail();

        $customer = Customer::where('id', $sale->customer->id)->firstOrFail();

        $data = [
            'sale'     => $sale,
            'customer' => $customer
        ];

        $pdf = PDF::loadView('admin.sale.print', $data, [], [
            'format' => 'a4',
        ]);

        return $pdf->stream(__('Sale').$sale->reference.'.pdf');
    }

    public function purchaseReturns($id)
    {
        $purchaseReturn = PurchaseReturn::where('id', $id)->firstOrFail();
        $supplier = Supplier::where('id', $purchaseReturn->supplier->id)->firstOrFail();

        $data = [
            'purchase_return' => $purchaseReturn,
            'supplier'        => $supplier
        ];

        $pdf = PDF::loadView('admin.purchasesreturn.print', $data);

        return $pdf->stream(__('Purchase Return').$purchaseReturn->reference.'.pdf');
    }

    public function quotation($id)
    {
        $quotation = Quotation::where('id', $id)->firstOrFail();
        $customer = Customer::where('id', $quotation->customer->id)->firstOrFail();

        $data = [
            'quotation' => $quotation,
            'customer'  => $customer,
        ];

        $pdf = PDF::loadView('admin.quotation.print', $data);

        return $pdf->stream(__('Quotation').$quotation->reference.'.pdf');
    }

    public function purchase($id)
    {
        $purchase = Purchase::with('supplier', 'purchaseDetails')->where('id', $id)->firstOrFail();
        $supplier = Supplier::where('id', $purchase->supplier->id)->firstOrFail();

        $data = [
            'purchase' => $purchase,
            'supplier' => $supplier
        ];
        
        // dd($data);
        $pdf = PDF::loadView('admin.purchases.print', $data, [], [
            'format' => 'a5',
        ]);
        

        return $pdf->stream(__('Purchase').$purchase->reference.'.pdf');
    }

    public function saleReturns($id)
    {
        $saleReturn = SaleReturn::where('id', $id)->firstOrFail();
        $customer = Customer::where('id', $saleReturn->customer->id)->firstOrFail();

        $data = [
            'sale_return' => $saleReturn,
            'customer'    => $customer
        ];

        $pdf = PDF::loadView('admin.salesreturn.print', $data);

        return $pdf->stream(__('Sale Return').$saleReturn->reference.'.pdf');
    }
}
