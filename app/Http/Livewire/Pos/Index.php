<?php

declare(strict_types=1);

namespace App\Http\Livewire\Pos;

use App\Enums\MovementType;
use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Jobs\PaymentNotification;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Models\ProductWarehouse;
use App\Models\SaleDetails;
use App\Models\SalePayment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'refreshCustomers',
    ];

    public $cart_instance;

    public $discountModal;

    public $warehouse_id;

    public $global_discount;

    public $global_tax;

    public $quantity;

    public $check_quantity;

    public $price;

    public $discount_type;

    public $item_discount;

    public $data;

    public $customer_id;

    public $total_amount;

    public $checkoutModal;

    public $product;

    public $paid_amount;

    public $tax_percentage;

    public $discount_percentage;

    public $discount_amount;

    public $tax_amount;

    public $grand_total;

    public $shipping_amount;

    public $payment_method;

    public $note;

    public $refreshCustomers;

    public $total_with_shipping;

    public $default_client;

    public $default_warehouse;

    public function rules(): array
    {
        return [
            'customer_id'         => 'required|numeric',
            'tax_percentage'      => 'required|integer|min:0|max:100',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'shipping_amount'     => 'nullable|numeric',
            'total_amount'        => 'required|numeric',
            'paid_amount'         => 'nullable|numeric',
            'note'                => 'nullable|string|max:1000',
            'price'               => 'nullable|numeric',
        ];
    }

    public function mount($cartInstance): void
    {
        $this->cart_instance = $cartInstance;
        $this->global_discount = 0;
        $this->global_tax = 0;
        $this->shipping_amount = 0.00;

        $this->check_quantity = [];
        $this->quantity = [];
        $this->discount_type = [];
        $this->item_discount = [];
        $this->payment_method = 'cash';

        $this->tax_percentage = 0;
        $this->discount_percentage = 0;
        $this->paid_amount = 0;

        $this->default_client = Customer::find(settings()->default_client_id);
        $this->default_warehouse = Warehouse::find(settings()->default_warehouse_id);

        $this->total_with_shipping = Cart::instance($this->cart_instance)->total() + (float) $this->shipping_amount;
    }

    public function hydrate(): void
    {
        if ($this->payment_method === 'cash') {
            $this->paid_amount = $this->total_amount;
        }
        $this->total_amount = $this->calculateTotal();
    }

    public function render()
    {
        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.pos.index', [
            'cart_items' => $cart_items,
        ]);
    }

    public function store(): void
    {
        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        DB::transaction(function () {
            $this->validate();

            // Determine payment status
            $due_amount = $this->total_amount - $this->paid_amount;

            if ($due_amount === $this->total_amount) {
                $payment_status = PaymentStatus::PENDING;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $sale = Sale::create([
                'date'                => date('Y-m-d'),
                'customer_id'         => $this->customer_id,
                'warehouse_id'        => $this->warehouse_id,
                'user_id'             => Auth::user()->id,
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount * 100,
                'paid_amount'         => $this->paid_amount * 100,
                'total_amount'        => $this->total_amount * 100,
                'due_amount'          => $due_amount * 100,
                'status'              => SaleStatus::COMPLETED,
                'payment_status'      => $payment_status,
                'payment_method'      => $this->payment_method,
                'note'                => $this->note,
                'tax_amount'          => Cart::instance('sale')->tax() * 100,
                'discount_amount'     => Cart::instance('sale')->discount() * 100,
            ]);

            // foreach ($this->cart_instance as cart_items) {}
            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id'                 => $sale->id,
                    'warehouse_id'            => $this->warehouse_id,
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

                $product = Product::findOrFail($cart_item->id);
                $product_warehouse = ProductWarehouse::where('product_id', $product->id)
                    ->where('warehouse_id', $this->warehouse_id)
                    ->first();

                $new_quantity = $product_warehouse->qty - $cart_item->qty;

                $product_warehouse->update([
                    'qty' => $new_quantity,
                ]);

                $movement = new Movement([
                    'type'         => MovementType::SALE,
                    'quantity'     => $cart_item->qty,
                    'price'        => $cart_item->price * 100,
                    'date'         => date('Y-m-d'),
                    'movable_type' => get_class($product),
                    'movable_id'   => $product->id,
                    'user_id'      => Auth::user()->id,
                ]);

                $movement->save();
            }

            Cart::instance('sale')->destroy();

            if ($sale->paid_amount > 0) {
                SalePayment::create([
                    'date'           => date('Y-m-d'),
                    'amount'         => $sale->paid_amount,
                    'sale_id'        => $sale->id,
                    'payment_method' => $this->payment_method,
                    'user_id'        => Auth::user()->id,
                ]);
            }

            $this->alert('success', __('Sale created successfully!'));

            $this->checkoutModal = false;

            Cart::instance('sale')->destroy();

            PaymentNotification::dispatch($sale);

            return redirect()->route('app.pos.index');
        });
    }

    // can you solve that issue please
    // customer should provoke checkout
    public function proceed(): void
    {
        if ($this->customer_id !== null) {
            $this->checkoutModal = true;
            $this->cart_instance = 'sale';
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function calculateTotal(): mixed
    {
        return Cart::instance($this->cart_instance)->total() + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        Cart::instance($this->cart_instance)->destroy();
    }

    public function getCustomersProperty()
    {
        return Customer::select(['name', 'id'])->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select(['name', 'id'])->get();
    }

    public function updatedWarehouseId($value)
    {
        $this->warehouse_id = $value;
        $this->emit('warehouseSelected', $this->warehouse_id);
    }
}
