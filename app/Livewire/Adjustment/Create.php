<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Livewire\Forms\AdjustmentForm;
use App\Livewire\Utils\WithModels;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Services\AdjustmentService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithAlert;
    use WithModels;

    public AdjustmentForm $form;

    public mixed $quantities;

    public mixed $types;

    public mixed $adjustment;

    public mixed $check_quantity;

    public mixed $quantity;

    #[Validate([
        'products.*.quantities' => 'required|integer|min:1',
        'products.*.types' => 'required|in:add,sub',
    ])]
    public array $products = [];

    public function mount(): void
    {
        $this->products = [];

        $this->form->reference = 'Adj-' . Str::random(5);
        $this->form->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->form->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.adjustment.create');
    }

    public function updatedFormWarehouseId(mixed $value): void
    {
        $this->form->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->warehouse_id);
    }

    public function store(AdjustmentService $adjustmentService): void
    {
        abort_if(Gate::denies('adjustment_create'), 403);

        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        try {
            $this->form->validate();
            $this->validate();

            $adjustmentService->createAdjustment(
                $this->form->all(),
                $this->products
            );

            $this->alert('success', __('Adjustment created successfully'));

            $this->redirectRoute('adjustments.index', navigate: true);
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error Occurred in ' . $throwable->getMessage());
        }
    }

    #[On('productSelected')]
    public function productSelected(mixed $productId, mixed $warehouseId = null): void
    {
        $product = Product::query()->find($productId);

        switch ($this->hasAdjustments) {
            case true:
                if (in_array($product, array_map(static fn (array $adjustment) => $adjustment['product'], $this->products))) {
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

        $productWarehouse = ProductWarehouse::query()->where('product_id', $productId)
            ->where('warehouse_id', $this->form->warehouse_id)
            ->first();

        $calculation = $this->calculate($product);

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'code' => $product->code,
            'weight' => 1,
            'options' => array_merge($calculation, [
                'product_discount' => 0.00,
                'product_discount_type' => 'fixed',
                'code' => $product->code,
                'stock' => $productWarehouse->qty,
                'unit' => $product->unit,
                'types' => 'add',
            ]),
        ];

        $this->updateQuantityAndCheckQuantity($product->id, $productWarehouse->qty);
    }

    private function updateQuantityAndCheckQuantity(mixed $productId, mixed $quantity): void
    {
        $this->check_quantity[$productId] = $quantity;
        $this->quantity[$productId] = 1;
    }

    public function calculate(mixed $product): array
    {
        $productWarehouse = ProductWarehouse::query()->where('product_id', $product->id)
            ->where('warehouse_id', $this->form->warehouse_id)
            ->first();

        return $this->calculatePrices($product, $productWarehouse);
    }

    private function calculatePrices(mixed $product, mixed $productWarehouse): array
    {
        $price = $productWarehouse->price;
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

    public function removeProduct(int|string $key): void
    {
        unset($this->products[$key]);
    }
}
