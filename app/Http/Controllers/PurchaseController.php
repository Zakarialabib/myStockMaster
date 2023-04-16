<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

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
}
