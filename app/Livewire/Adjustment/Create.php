<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Livewire\Utils\WithModels;
use App\Models\AdjustedProduct;
use App\Models\Adjustment;
use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Throwable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Create extends Component
{
    use LivewireAlert;
    use WithModels;

    #[Validate('required|date')]
    public $date;

    #[Validate('nullable|string|max:1000')]
    public $note;

    #[Validate('required|string|max:255')]
    public $reference;

    public $quantities;

    public $types;

    public $warehouse_id;

    public $adjustment;

    public $check_quantity;

    public $quantity;

    public $products;

    public $hasAdjustments;

    protected $rules = [
        'products.*.quantities' => 'required|integer|min:1',
        'products.*.types'      => 'required|in:add,sub',
    ];

    public function mount(): void
    {
        $this->products = [];

        $this->reference = 'Adj-'.Str::random(5);
        $this->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render()
    {
        return view('livewire.adjustment.create');
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }

    public function store()
    {
        abort_if(Gate::denies('adjustment_create'), 403);

        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        try {
            $this->validate();

            $adjustment = Adjustment::create([
                'date'         => $this->date,
                'note'         => $this->note,
                'user_id'      => auth()->id(),
                'warehouse_id' => $this->warehouse_id,
            ]);

            foreach ($this->products as $product) {
                AdjustedProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id'    => $product['id'],
                    'warehouse_id'  => $this->warehouse_id,
                    'quantity'      => $product['quantities'],
                    'type'          => $product['types'],
                ]);

                $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
                    ->where('warehouse_id', $this->warehouse_id)
                    ->first();

                if ($product['types'] === 'add') {
                    $productWarehouse->update([
                        'qty' => $productWarehouse->qty + $product['quantities'],
                    ]);
                } elseif ($product['types'] === 'sub') {
                    $productWarehouse->update([
                        'qty' => $productWarehouse->qty - $product['quantities'],
                    ]);
                }
            }

            $this->alert('success', __('Adjustment created successfully'));

            return redirect()->route('adjustments.index');
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error Occurred in '.$throwable->getMessage());
        }
    }

    #[On('productSelected')]
    public function productSelected($id): void
    {
        $product = Product::find($id);

        switch ($this->hasAdjustments) {
            case true:
                if (in_array($product, array_map(static fn ($adjustment) => $adjustment['product'], $this->products))) {
                    $this->alert('error', __('Product added succesfully'));

                    return;
                }

                break;
            case false:
                if (in_array($product, $this->products)) {
                    $this->alert('error', __('Already exists in the product list!'));

                    return;
                }

                break;
            default:
                $this->alert('error', __('Something went wrong!'));

                return;
        }

        // add default quantities and types to the product array

        $productWarehouse = ProductWarehouse::where('product_id', $id)
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        $calculation = $this->calculate($product);

        $this->products[] = [
            'id'      => $product->id,
            'name'    => $product->name,
            'qty'     => 1,
            'code'    => $product->code,
            'weight'  => 1,
            'options' => array_merge($calculation, [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'code'                  => $product->code,
                'stock'                 => $productWarehouse->qty,
                'unit'                  => $product->unit,
                'types'                 => 'add',
            ]),
        ];

        $this->updateQuantityAndCheckQuantity($product->id, $productWarehouse->qty);
    }

    private function updateQuantityAndCheckQuantity($productId, $quantity): void
    {
        $this->check_quantity[$productId] = $quantity;
        $this->quantity[$productId] = 1;
    }

    public function calculate($product): array
    {
        $productWarehouse = ProductWarehouse::where('product_id', $product->id)
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        return $this->calculatePrices($product, $productWarehouse);
    }

    private function calculatePrices($product, $productWarehouse)
    {
        $price = $productWarehouse->price * 100;
        $unit_price = $price;
        $product_tax = 0.00;
        $sub_total = $price;

        if ($product->tax_type === 1) {
            $tax = $price * $product->tax_amount / 100;
            $price += $tax;
            $product_tax = $tax;
            $sub_total = $price;
        } elseif ($product->tax_type === 2) {
            $tax = $price * $product->tax_amount / 100;
            $unit_price -= $tax;
            $product_tax = $tax;
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    public function removeProduct($key): void
    {
        unset($this->products[$key]);
    }
}
