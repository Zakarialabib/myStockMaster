<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class QuotationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('quotation_access'), 403);

        return view('admin.quotation.index');
    }

    public function create()
    {
        abort_if(Gate::denies('quotation_create'), 403);

        return view('admin.quotation.create');
    }

    // use livewire --------->
    public function store(StoreQuotationRequest $request)
    {
        DB::transaction(function () use ($request) {
            $quotation = Quotation::create([
                'date'                => $request->date,
                'customer_id'         => $request->customer_id,
                'user_id'             => Auth::user()->id,
                'tax_percentage'      => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount'     => $request->shipping_amount * 100,
                'total_amount'        => $request->total_amount * 100,
                'status'              => $request->status,
                'note'                => $request->note,
                'tax_amount'          => Cart::instance('quotation')->tax() * 100,
                'discount_amount'     => Cart::instance('quotation')->discount() * 100,
            ]);

            foreach (Cart::instance('quotation')->content() as $cart_item) {
                QuotationDetails::create([
                    'quotation_id'            => $quotation->id,
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
            }

            Cart::instance('quotation')->destroy();
        });

        // toast('Quotation Created!', 'success');

        return redirect()->route('quotations.index');
    }

    public function edit(Quotation $quotation)
    {
        abort_if(Gate::denies('quotation_update'), 403);

        $quotation_details = $quotation->quotationDetails;

        Cart::instance('quotation')->destroy();

        $cart = Cart::instance('quotation');

        foreach ($quotation_details as $quotation_detail) {
            $cart->add([
                'id'      => $quotation_detail->product_id,
                'name'    => $quotation_detail->name,
                'qty'     => $quotation_detail->quantity,
                'price'   => $quotation_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount'      => $quotation_detail->product_discount_amount,
                    'product_discount_type' => $quotation_detail->product_discount_type,
                    'sub_total'             => $quotation_detail->sub_total,
                    'code'                  => $quotation_detail->code,
                    'stock'                 => Product::findOrFail($quotation_detail->product_id)->quantity,
                    'product_tax'           => $quotation_detail->product_tax_amount,
                    'unit_price'            => $quotation_detail->unit_price,
                ],
            ]);
        }

        return view('admin.quotation.edit', compact('quotation'));
    }

    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        DB::transaction(function () use ($request, $quotation) {
            foreach ($quotation->quotationDetails as $quotation_detail) {
                $quotation_detail->delete();
            }

            $quotation->update([
                'date'                => $request->date,
                'reference'           => $request->reference,
                'customer_id'         => $request->customer_id,
                'tax_percentage'      => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount'     => $request->shipping_amount * 100,
                'total_amount'        => $request->total_amount * 100,
                'status'              => $request->status,
                'note'                => $request->note,
                'tax_amount'          => Cart::instance('quotation')->tax() * 100,
                'discount_amount'     => Cart::instance('quotation')->discount() * 100,
            ]);

            foreach (Cart::instance('quotation')->content() as $cart_item) {
                QuotationDetails::create([
                    'quotation_id'            => $quotation->id,
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
            }

            Cart::instance('quotation')->destroy();
        });

        // toast('Quotation Updated!', 'info');

        return redirect()->route('quotations.index');
    }
}
