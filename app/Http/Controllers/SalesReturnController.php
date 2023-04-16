<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\StoreSaleReturnRequest;
use App\Http\Requests\UpdateSaleReturnRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnPayment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SalesReturnController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sale_return_access'), 403);

        return view('admin.salesreturn.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sale_return_create'), 403);

        Cart::instance('sale_return')->destroy();

        return view('admin.salesreturn.create');
    }

    // use livewire --------->
    public function store(StoreSaleReturnRequest $request)
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

            $sale_return = SaleReturn::create([
                'date'                => $request->date,
                'customer_id'         => $request->customer_id,
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
                'tax_amount'          => Cart::instance('sale_return')->tax() * 100,
                'discount_amount'     => Cart::instance('sale_return')->discount() * 100,
            ]);

            foreach (Cart::instance('sale_return')->content() as $cart_item) {
                SaleReturnDetail::create([
                    'sale_return_id'  => $sale_return->id,
                    'product_id'      => $cart_item->id,
                    'name'            => $cart_item->name,
                    'code'            => $cart_item->options->code,
                    'quantity'        => $cart_item->qty,
                    'price'           => $cart_item->price * 100,
                    'unit_price'      => $cart_item->options->unit_price * 100,
                    'sub_total'       => $cart_item->options->sub_total * 100,
                    'discount_amount' => $cart_item->options->discount * 100,
                    'discount_type'   => $cart_item->options->discount_type,
                    'tax_amount'      => $cart_item->options->tax * 100,
                ]);

                if ($request->status === '2') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'quantity' => $product->quantity + $cart_item->qty,
                    ]);
                }
            }

            Cart::instance('sale_return')->destroy();

            if ($sale_return->paid_amount > 0) {
                SaleReturnPayment::create([
                    'date'           => $request->date,
                    'reference'      => 'INV/'.$sale_return->reference,
                    'amount'         => $sale_return->paid_amount,
                    'sale_return_id' => $sale_return->id,
                    'payment_method' => $request->payment_method,
                ]);
            }
        });

        // toast('Sale Return Created!', 'success');

        return redirect()->route('sale-returns.index');
    }

    public function show(SaleReturn $sale_return)
    {
        abort_if(Gate::denies('show_sale_returns'), 403);

        $customer = Customer::findOrFail($sale_return->customer_id);

        return view('admin.salesreturn.show', compact('sale_return', 'customer'));
    }

    public function edit(SaleReturn $sale_return)
    {
        abort_if(Gate::denies('sale_return_update'), 403);

        $sale_return_details = $sale_return->saleReturnDetails;

        Cart::instance('sale_return')->destroy();

        $cart = Cart::instance('sale_return');

        foreach ($sale_return_details as $sale_return_detail) {
            $cart->add([
                'id'      => $sale_return_detail->product_id,
                'name'    => $sale_return_detail->name,
                'qty'     => $sale_return_detail->quantity,
                'price'   => $sale_return_detail->price,
                'weight'  => 1,
                'options' => [
                    'discount'      => $sale_return_detail->discount_amount,
                    'discount_type' => $sale_return_detail->discount_type,
                    'sub_total'     => $sale_return_detail->sub_total,
                    'code'          => $sale_return_detail->code,
                    'stock'         => Product::findOrFail($sale_return_detail->product_id)->quantity,
                    'tax'           => $sale_return_detail->tax_amount,
                    'unit_price'    => $sale_return_detail->unit_price,
                ],
            ]);
        }

        return view('admin.salesreturn.edit', compact('sale_return'));
    }

    public function update(UpdateSaleReturnRequest $request, SaleReturn $sale_return)
    {
        DB::transaction(function () use ($request, $sale_return) {
            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount === $request->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            foreach ($sale_return->saleReturnDetails as $sale_return_detail) {
                if ($sale_return->status === 'Completed') {
                    $product = Product::findOrFail($sale_return_detail->product_id);
                    $product->update([
                        'quantity' => $product->quantity - $sale_return_detail->quantity,
                    ]);
                }
                $sale_return_detail->delete();
            }

            $sale_return->update([
                'date'                => $request->date,
                'reference'           => $request->reference,
                'customer_id'         => $request->customer_id,
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
                'tax_amount'          => Cart::instance('sale_return')->tax() * 100,
                'discount_amount'     => Cart::instance('sale_return')->discount() * 100,
            ]);

            foreach (Cart::instance('sale_return')->content() as $cart_item) {
                SaleReturnDetail::create([
                    'sale_return_id'  => $sale_return->id,
                    'product_id'      => $cart_item->id,
                    'name'            => $cart_item->name,
                    'code'            => $cart_item->options->code,
                    'quantity'        => $cart_item->qty,
                    'price'           => $cart_item->price * 100,
                    'unit_price'      => $cart_item->options->unit_price * 100,
                    'sub_total'       => $cart_item->options->sub_total * 100,
                    'discount_amount' => $cart_item->options->discount * 100,
                    'discount_type'   => $cart_item->options->discount_type,
                    'tax_amount'      => $cart_item->options->tax * 100,
                ]);

                if ($request->status === 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'quantity' => $product->quantity + $cart_item->qty,
                    ]);
                }
            }

            Cart::instance('sale_return')->destroy();
        });

        // toast('Sale Return Updated!', 'info');

        return redirect()->route('sale-returns.index');
    }

    public function destroy(SaleReturn $sale_return)
    {
        abort_if(Gate::denies('sale_return_delete'), 403);

        $sale_return->delete();

        // toast('Sale Return Deleted!', 'warning');

        return redirect()->route('sale-returns.index');
    }
}
