<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;

class PurchaseController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('purchase_access'), 403);

        return view('admin.purchases.index');
    }

    public function create()
    {
        abort_if(Gate::denies('purchase_create'), 403);

        Cart::instance('purchase')->destroy();

        return view('admin.purchases.create');
    }

 

    public function edit(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $purchase_details = $purchase->purchaseDetails;

        Cart::instance('purchase')->destroy();

        $cart = Cart::instance('purchase');

        foreach ($purchase_details as $purchase_detail) {
            $cart->add([
                'id'      => $purchase_detail->product_id,
                'name'    => $purchase_detail->name,
                'qty'     => $purchase_detail->quantity,
                'price'   => $purchase_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount'      => $purchase_detail->product_discount_amount,
                    'product_discount_type' => $purchase_detail->product_discount_type,
                    'sub_total'             => $purchase_detail->sub_total,
                    'code'                  => $purchase_detail->code,
                    'stock'                 => Product::findOrFail($purchase_detail->product_id)->quantity,
                    'product_tax'           => $purchase_detail->product_tax_amount,
                    'unit_price'            => $purchase_detail->unit_price,
                ],
            ]);
        }

        return view('admin.purchases.edit', compact('purchase'));
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::transaction(function () use ($request, $purchase) {
            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount == $request->total_amount) {
                $payment_status = PaymentStatus::Pending;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::Partial;
            } else {
                $payment_status = PaymentStatus::Paid;
            }

            foreach ($purchase->purchaseDetails as $purchase_detail) {
                if ($purchase->status == PurchaseStatus::Completed) {
                    $product = Product::findOrFail($purchase_detail->product_id);
                    $product->update([
                        'quantity' => $product->quantity - $purchase_detail->quantity,
                    ]);
                }
                $purchase_detail->delete();
            }

            $purchase->update([
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
                'tax_amount'          => Cart::instance('purchase')->tax() * 100,
                'discount_amount'     => Cart::instance('purchase')->discount() * 100,
            ]);

            foreach (Cart::instance('purchase')->content() as $cart_item) {
                PurchaseDetail::create([
                    'purchase_id'             => $purchase->id,
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

                if ($request->status == PurchaseStatus::Completed) {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'quantity' => $product->quantity + $cart_item->qty,
                    ]);
                }
            }

            Cart::instance('purchase')->destroy();
        });

        // toast('Purchase Updated!', 'info');

        return redirect()->route('purchases.index');
    }
}
