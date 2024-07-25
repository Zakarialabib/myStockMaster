<?php

declare(strict_types=1);

namespace App\Http\Livewire\Adjustment;

use App\Models\AdjustedProduct;
use App\Models\Adjustment;
use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    public $date;
    public $note;
    public $reference;
    public $quantities;
    public $types;
    public $warehouse_id;

    public $products;

    public $hasAdjustments;

    protected $listeners = [
        'warehouseSelected' => 'updatedWarehouseId',
        'productSelected',
    ];

    protected $rules = [
        'reference'             => 'required|string|max:255',
        'date'                  => 'required|date',
        'note'                  => 'nullable|string|max:1000',
        'products.*.quantities' => 'required|integer|min:1',
        'products.*.types'      => 'required|in:add,sub',
    ];

    public function mount()
    {
        $this->products = [];

        $this->reference = 'Adj-'.Str::random(5);
        $this->date = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.adjustment.create');
    }

    public function updatedWarehouseId($value)
    {
        $this->warehouse_id = $value;
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
                'date' => $this->date,
                'note' => $this->note,
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
        } catch (Throwable $th) {
            $this->alert('error', 'Error Occurred in '.$th->getMessage());
        }
    }

    public function productSelected($product): void
    {
        switch ($this->hasAdjustments) {
            case true:
                if (in_array($product, array_map(function ($adjustment) {
                    return $adjustment['product'];
                }, $this->products))) {
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
        $product['quantities'] = 10;
        $product['types'] = 'add';

        array_push($this->products, $product);
    }

    public function removeProduct($key): void
    {
        unset($this->products[$key]);
    }
}
