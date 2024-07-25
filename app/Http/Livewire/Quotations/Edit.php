<?php

namespace App\Http\Livewire\Quotations;

use Livewire\Component;
use App\Models\Quoation;
use App\Models\QuotationDetails;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Edit extends Component
{
    use LivewireAlert;

    public $quotation;
    public $quotation_details;

    protected $rules = [
        'quotation.customer_id'         => 'required|numeric',
        'quotation.reference'           => 'required|string|max:255',
        'quotation.date'           => 'required|date',
        'quotation.tax_percentage'      => 'required|integer|min:0|max:100',
        'quotation.discount_percentage' => 'required|integer|min:0|max:100',
        'quotation.shipping_amount'     => 'required|numeric',
        'quotation.total_amount'        => 'required|numeric',
        'quotation.status'              => 'required|string|max:255',
        'quotation.note'                => 'nullable|string|max:1000',
    ];
    public function render()
    {
        return view('livewire.quotations.edit');
    }

    public function edit(Quotation $quotation)
    {
        abort_if(Gate::denies('quotation_update'), 403);

        $this->quotation_details = $quotation->quotationDetails;
        // $this->quotation->date = date('Y-m-d');

        Cart::instance('quotation')->destroy();

        $cart = Cart::instance('quotation');

        foreach ($this->quotation_details as $quotation_detail) {
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
    }

    public function update()
    {
        $this->validate();
        foreach ($this->quotation->quotationDetails as $quotation_detail) {
            $quotation_detail->delete();
        }

        $this->quotation->update([
            'date'                => $this->quotation->date,
            'reference'           => $this->quotation->reference,
            'customer_id'         => $this->quotation->customer_id,
            'tax_percentage'      => $this->quotation->tax_percentage,
            'discount_percentage' => $this->quotation->discount_percentage,
            'shipping_amount'     => $this->quotation->shipping_amount * 100,
            'total_amount'        => $this->quotation->total_amount * 100,
            'status'              => $this->quotation->status,
            'note'                => $this->quotation->note,
            'tax_amount'          => Cart::instance('quotation')->tax() * 100,
            'discount_amount'     => Cart::instance('quotation')->discount() * 100,
        ]);

        foreach (Cart::instance('quotation')->content() as $cart_item) {
            QuotationDetails::create([
                'quotation_id'            => $this->quotation->id,
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

        return redirect()->route('quotations.index');
    }
}
