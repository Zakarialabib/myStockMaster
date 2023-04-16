<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\StorePurchaseReturnRequest;
use App\Http\Requests\UpdatePurchaseReturnRequest;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnPayment;
use App\Models\Supplier;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PurchasesReturnController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('purchase_return_access'), 403);

        return view('admin.purchasesreturn.index');
    }

    public function create()
    {
        abort_if(Gate::denies('purchase_return_create'), 403);

        $supplier = Supplier::select(['id', 'name'])->get();

        Cart::instance('purchase_return')->destroy();

        return view('admin.purchasesreturn.create', compact('supplier'));
    }

    public function store(StorePurchaseReturnRequest $request)
    {
        DB::transaction(function () use ($request) {
            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount === $request->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $purchase_return = PurchaseReturn::create([
                'date'                => $request->date,
                'supplier_id'         => $request->supplier_id,
                'user_id'             => Auth::user()->id,
                'tax_percentage'      => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount'     => $request->shipping_amount * 100,
                'paid_amount'         => $request->paid_amount * 100,
                'total_amount'        => $request->total_amount * 100,
                'due_amount'          => $due_amount * 100,
                'status'              => $request->status,
                'payment_status'      => $payment_status,
                'payment_method'      => $request->payment_method,
                'note'                => $request->note,
                'tax_amount'          => Cart::instance('purchase_return')->tax() * 100,
                'discount_amount'     => Cart::instance('purchase_return')->discount() * 100,
            ]);

            foreach (Cart::instance('purchase_return')->content() as $cart_item) {
                PurchaseReturnDetail::create([
                    'purchase_return_id'      => $purchase_return->id,
                    'product_id'              => $cart_item->id,
                    'name'                    => $cart_item->name,
                    'code'                    => $cart_item->options->code,
                    'quantity'                => $cart_item->qty,
                    'price'                   => $cart_item->price * 100,
                    'unit_price'              => $cart_item->options->unit_price * 100,
                    'sub_total'               => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type'   => $cart_item->options->product_discount_type,
                    'product_tax_amount'      => $cart_item->options->product_tax * 100,
                ]);

                if ($request->status === 'Shipped' || $request->status === 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'quantity' => $product->quantity - $cart_item->qty,
                    ]);
                }
            }

            Cart::instance('purchase_return')->destroy();

            if ($purchase_return->paid_amount > 0) {
                PurchaseReturnPayment::create([
                    'date'               => $request->date,
                    'reference'          => 'INV/'.$purchase_return->reference,
                    'amount'             => $purchase_return->paid_amount,
                    'purchase_return_id' => $purchase_return->id,
                    'payment_method'     => $request->payment_method,
                ]);
            }
        });

        // toast('Purchase Return Created!', 'success');

        return redirect()->route('purchase-returns.index');
    }

    public function show(PurchaseReturn $purchase_return)
    {
        abort_if(Gate::denies('purchase_return_show'), 403);

        $supplier = Supplier::findOrFail($purchase_return->supplier_id);

        return view('admin.purchasesreturn.show', compact('purchase_return', 'supplier'));
    }

    public function edit(PurchaseReturn $purchase_return)
    {
        abort_if(Gate::denies('purchase_return_update'), 403);

        $supplier = Supplier::select(['id', 'name'])->get();

        $purchase_return_details = $purchase_return->purchaseReturnDetails;
        Cart::instance('purchase_return')->destroy();

        $cart = Cart::instance('purchase_return');

        foreach ($purchase_return_details as $purchase_return_detail) {
            $cart->add([
                'id'      => $purchase_return_detail->product_id,
                'name'    => $purchase_return_detail->name,
                'qty'     => $purchase_return_detail->quantity,
                'price'   => $purchase_return_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount'      => $purchase_return_detail->product_discount_amount,
                    'product_discount_type' => $purchase_return_detail->product_discount_type,
                    'sub_total'             => $purchase_return_detail->sub_total,
                    'code'                  => $purchase_return_detail->code,
                    'stock'                 => Product::findOrFail($purchase_return_detail->product_id)->quantity,
                    'product_tax'           => $purchase_return_detail->product_tax_amount,
                    'unit_price'            => $purchase_return_detail->unit_price,
                ],
            ]);
        }

        return view('admin.purchasesreturn.edit', compact('purchase_return', 'supplier'));
    }

    public function update(UpdatePurchaseReturnRequest $request, PurchaseReturn $purchase_return)
    {
        DB::transaction(function () use ($request, $purchase_return) {
            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount === $request->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            foreach ($purchase_return->purchaseReturnDetails as $purchase_return_detail) {
                if ($purchase_return->status === 'Shipped' || $purchase_return->status === 'Completed') {
                    $product = Product::findOrFail($purchase_return_detail->product_id);
                    $product->update([
                        'quantity' => $product->quantity + $purchase_return_detail->quantity,
                    ]);
                }
                $purchase_return_detail->delete();
            }

            $purchase_return->update([
                'date'                => $request->date,
                'reference'           => $request->reference,
                'supplier_id'         => $request->supplier_id,
                'tax_percentage'      => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount'     => $request->shipping_amount * 100,
                'paid_amount'         => $request->paid_amount * 100,
                'total_amount'        => $request->total_amount * 100,
                'due_amount'          => $due_amount * 100,
                'status'              => $request->status,
                'payment_status'      => $payment_status,
                'payment_method'      => $request->payment_method,
                'note'                => $request->note,
                'tax_amount'          => Cart::instance('purchase_return')->tax() * 100,
                'discount_amount'     => Cart::instance('purchase_return')->discount() * 100,
            ]);

            foreach (Cart::instance('purchase_return')->content() as $cart_item) {
                PurchaseReturnDetail::create([
                    'purchase_return_id'      => $purchase_return->id,
                    'product_id'              => $cart_item->id,
                    'name'                    => $cart_item->name,
                    'code'                    => $cart_item->options->code,
                    'quantity'                => $cart_item->qty,
                    'price'                   => $cart_item->price * 100,
                    'unit_price'              => $cart_item->options->unit_price * 100,
                    'sub_total'               => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type'   => $cart_item->options->product_discount_type,
                    'product_tax_amount'      => $cart_item->options->product_tax * 100,
                ]);

                if ($request->status === 'Shipped' || $request->status === 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'quantity' => $product->quantity - $cart_item->qty,
                    ]);
                }
            }

            Cart::instance('purchase_return')->destroy();
        });

        // toast('Purchase Return Updated!', 'info');

        return redirect()->route('purchase-returns.index');
    }

    public function destroy(PurchaseReturn $purchase_return)
    {
        abort_if(Gate::denies('purchase_return_delete'), 403);

        $purchase_return->delete();

        // toast('Purchase Return Deleted!', 'warning');

        return redirect()->route('purchase-returns.index');
    }
}
