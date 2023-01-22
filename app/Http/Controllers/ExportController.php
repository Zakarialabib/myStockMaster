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
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function salePos($id)
    {
        $sale = Sale::findOrFail($id);

        $pdf = PDF::loadView('admin.sale.print-pos', [
            'sale' => $sale,
        ])->setPaper('a7')
            ->setOption('margin-top', 8)
            ->setOption('margin-bottom', 8)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5);

        return $pdf->stream(__('Sale').$sale->reference.'.pdf');
    }

    public function sale($id)
    {
        $sale = Sale::findOrFail($id);
        $customer = Customer::findOrFail($sale->customer_id);

        $pdf = PDF::loadView('admin.sale.print', [
            'sale'     => $sale,
            'customer' => $customer,
        ])->setPaper('a4');

        return $pdf->stream(__('Sale').$sale->reference.'.pdf');
    }

    public function purchaseReturns($id)
    {
        $purchaseReturn = PurchaseReturn::findOrFail($id);
        $supplier = Supplier::findOrFail($purchaseReturn->supplier_id);

        $pdf = PDF::loadView('admin.purchasesreturn.print', [
            'purchase_return' => $purchaseReturn,
            'supplier'        => $supplier,
        ])->setPaper('a4');

        return $pdf->stream(__('Purchase Return').$purchaseReturn->reference.'.pdf');
    }

    public function quotation($id)
    {
        $quotation = Quotation::findOrFail($id);
        $customer = Customer::findOrFail($quotation->customer_id);

        $pdf = PDF::loadView('admin.quotation.print', [
            'quotation' => $quotation,
            'customer'  => $customer,
        ])->setPaper('a4');

        return $pdf->stream(__('Quotation').$quotation->reference.'.pdf');
    }

    public function purchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        $supplier = Supplier::findOrFail($purchase->supplier_id);

        $pdf = PDF::loadView('admin.purchases.print', [
            'purchase' => $purchase,
            'supplier' => $supplier,
        ])->setPaper('a4');

        return $pdf->stream(__('Purchase').$purchase->reference.'.pdf');
    }

    public function saleReturns($id)
    {
        $saleReturn = SaleReturn::findOrFail($id);
        $customer = Customer::findOrFail($saleReturn->customer_id);

        $pdf = PDF::loadView('admin.salesreturn.print', [
            'sale_return' => $saleReturn,
            'customer'    => $customer,
        ])->setPaper('a4');

        return $pdf->stream(__('Sale Return').$saleReturn->reference.'.pdf');
    }
}
