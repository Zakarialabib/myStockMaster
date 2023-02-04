<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Quotation;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class QuotationSalesController extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        abort_if(Gate::denies('quotation_sale'), 403);

        $quotation_details = $quotation->quotationDetails;

        Cart::instance('sale')->destroy();

        $cart = Cart::instance('sale');

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

        return view('admin.quotation.quotation-sales.create', [
            'quotation_id' => $quotation->id,
            'sale'         => $quotation,
        ]);
    }
}
