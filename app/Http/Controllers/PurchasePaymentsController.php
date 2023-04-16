<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PurchasePaymentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('purchase_payment_access'), 403);

        return view('admin.purchases.payments.index');
    }

    public function create($purchase_id)
    {
        abort_if(Gate::denies('purchase_payment_create'), 403);

        $purchase = Purchase::findOrFail($purchase_id);

        return view('admin.purchases.payments.create', compact('purchase'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'           => 'required|date',
            'reference'      => 'required|string|max:255',
            'amount'         => 'required|numeric',
            'note'           => 'nullable|string|max:1000',
            'purchase_id'    => 'required',
            'payment_method' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            PurchasePayment::create([
                'date'           => $request->date,
                'amount'         => $request->amount,
                'note'           => $request->note,
                'purchase_id'    => $request->purchase_id,
                'payment_method' => $request->payment_method,
            ]);

            $purchase = Purchase::findOrFail($request->purchase_id);

            $due_amount = $purchase->due_amount - $request->amount;

            if ($due_amount === $purchase->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $purchase->update([
                'paid_amount'    => ($purchase->paid_amount + $request->amount) * 100,
                'due_amount'     => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);
        });

        // toast('Purchase Payment Created!', 'success');

        return redirect()->route('purchases.index');
    }

    public function edit($purchase_id, PurchasePayment $purchasePayment)
    {
        abort_if(Gate::denies('purchase_payment_update'), 403);

        $purchase = Purchase::findOrFail($purchase_id);

        return view('admin.purchases.payments.edit', compact('purchasePayment', 'purchase'));
    }

    public function update(Request $request, PurchasePayment $purchasePayment)
    {
        $request->validate([
            'date'           => 'required|date',
            'reference'      => 'required|string|max:255',
            'amount'         => 'required|numeric',
            'note'           => 'nullable|string|max:1000',
            'purchase_id'    => 'required',
            'payment_method' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $purchasePayment) {
            $purchase = $purchasePayment->purchase;

            $due_amount = $purchase->due_amount + $purchasePayment->amount - $request->amount;

            if ($due_amount === $purchase->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $purchase->update([
                'paid_amount'    => ($purchase->paid_amount - $purchasePayment->amount + $request->amount) * 100,
                'due_amount'     => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);

            $purchasePayment->update([
                'date'           => $request->date,
                'reference'      => $request->reference,
                'amount'         => $request->amount,
                'note'           => $request->note,
                'purchase_id'    => $request->purchase_id,
                'payment_method' => $request->payment_method,
            ]);
        });

        // toast('Purchase Payment Updated!', 'info');

        return redirect()->route('purchases.index');
    }

    public function destroy(PurchasePayment $purchasePayment)
    {
        abort_if(Gate::denies('purchase_payment_delete'), 403);

        $purchasePayment->delete();

        // toast('Purchase Payment Deleted!', 'warning');

        return redirect()->route('purchases.index');
    }
}
