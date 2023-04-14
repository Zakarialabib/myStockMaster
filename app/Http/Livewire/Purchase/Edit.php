<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Models\Product;

class Edit extends Component
{

    use LivewireAlert;

    /** @var array<string> */
    public $listeners = [
        'productSelected',
        'refreshIndex' => '$refresh'
    ];

    public $suppliers;

    public $purchase;

    public $products;

    public $supplier_id;

    public $product;

    public $quantity;

    public $reference;

    public $total_amount;

    public $check_quantity;

    public $price;

    public $tax_percentage;

    public $discount_percentage;

    public $shipping_amount;

    public $shipping;

    public $paid_amount;

    public $note;

    public $status;

    public $payment_method;

    public $date;
    public $discount_type;
    public $item_discount;
    public $listsForFields = [];

    public function rules(): array
    {
        return [
            'supplier_id'         => 'required|numeric',
            'reference'           => 'required|string|max:255',
            'tax_percentage'      => 'required|integer|min:0|max:100',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'shipping_amount'     => 'required|numeric',
            'total_amount'        => 'required|numeric',
            'paid_amount'         => 'required|numeric',
            'status'              => 'required|integer|max:255',
            'payment_method'      => 'required|string|max:255',
            'note'                => 'nullable|string|max:1000',
        ];
    }

    public function mount(Purchase $purchase)
    {
        $this->purchase = Purchase::findOrFail($purchase->id);
        $this->reference = $this->purchase->reference;
        $this->date = $this->purchase->date;
        $this->supplier_id = $this->purchase->supplier_id;
        $this->status = $this->purchase->status;
        $this->payment_method = $this->purchase->payment_method;
        $this->paid_amount = $this->purchase->paid_amount;
        $this->note = $this->purchase->note;
        $this->tax_percentage = $this->purchase->tax_percentage;
        $this->discount_percentage = $this->purchase->discount_percentage;
        $this->shipping_amount = $this->purchase->shipping_amount;
        $this->total_amount = $this->purchase->total_amount;
    }

    public function update()
    {

        $this->validate();


        // Only allow updates to PENDING or ORDERED purchases
        if ($this->purchase->status === PurchaseStatus::COMPLETED || $this->purchase->status === PurchaseStatus::RETURNED || $this->purchase->status === PurchaseStatus::CANCELED) {
            $this->alert('error', __('Cannot update a completed, returned or canceled purchase.'));
            return redirect()->back();
        }
        
        // Get the payment status based on the paid amount
        $due_amount = $this->total_amount - $this->paid_amount;
        if ($due_amount === $this->total_amount) {
            $payment_status = PaymentStatus::PENDING;
        } elseif ($due_amount > 0) {
            $payment_status = PaymentStatus::PARTIAL;
        } else {
            $payment_status = PaymentStatus::PAID;
        }
        
        // Update the purchase details and adjust the product quantities accordingly
        foreach ($this->purchase->purchaseDetails as $purchase_detail) {
            if ($this->purchase->status === PurchaseStatus::COMPLETED) {
                $product = Product::findOrFail($purchase_detail->product_id);
                $product->update([
                    'quantity' => $product->quantity - $purchase_detail->quantity,
                ]);
            }
            $purchase_detail->delete();
        }

        $this->purchase->update([
            'date'                => $this->date,
            'reference'           => $this->reference,
            'supplier_id'         => $this->supplier_id,
            'tax_percentage'      => $this->tax_percentage,
            'discount_percentage' => $this->discount_percentage,
            'shipping_amount'     => $this->shipping_amount * 100,
            'paid_amount'         => $this->paid_amount * 100,
            'total_amount'        => $this->total_amount * 100,
            'due_amount'          => $due_amount * 100,
            'status'              => $this->purchase->status,
            'payment_status'      => $payment_status,
            'payment_method'      => $this->payment_method,
            'note'                => $this->note,
            'tax_amount'          => Cart::instance('purchase')->tax() * 100,
            'discount_amount'     => Cart::instance('purchase')->discount() * 100,
        ]);

        foreach (Cart::instance('purchase')->content() as $cart_item) {
            PurchaseDetail::create([
                'purchase_id'             => $this->purchase->id,
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

            if ($this->status === PurchaseStatus::COMPLETED) {
                $product = Product::findOrFail($cart_item->id);
                $product->update([
                    'quantity' => $product->quantity + $cart_item->qty,
                ]);
            }
        }

        Cart::instance('purchase')->destroy();

        $this->alert('success', __('Purchase Updated succesfully !'));

        return redirect()->route('purchases.index');
    }

    public function render()
    {
        return view('livewire.purchase.edit');
    }

    public function getSupplierProperty()
    {
        return Supplier::select('name', 'id')->get();
    }
}
