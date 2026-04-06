<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Livewire\Forms\AdjustmentForm;
use App\Livewire\Utils\WithModels;
use App\Models\Adjustment;
use App\Services\AdjustmentService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithAlert;
    use WithModels;

    public AdjustmentForm $form;

    public $adjustment;

    public $quantity;

    public $type;

    #[Validate([
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.type' => 'required|in:add,sub',
    ])]
    public $products;

    public $hasAdjustments;

    public function mount($id): void
    {
        $this->adjustment = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
            ->where('id', $id)->first();

        $this->form->date = $this->adjustment->date;
        $this->form->warehouse_id = $this->adjustment->warehouse->id;

        $this->form->reference = $this->adjustment->reference;
        $this->form->note = $this->adjustment->note;

        $this->products = $this->adjustment->adjustedProducts->toArray();
    }

    public function update(AdjustmentService $adjustmentService)
    {
        abort_if(Gate::denies('adjustment_update'), 403);

        $this->form->validate();
        $this->validate();

        $adjustmentService->updateAdjustment(
            $this->adjustment,
            $this->form->all(),
            $this->products
        );

        return redirect()->route('adjustments.index');
    }

    #[On('productSelected')]
    public function productSelected(array $product): void
    {
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

        $this->products[] = $product;
    }

    public function removeProduct($key): void
    {
        unset($this->products[$key]);
    }

    #[On('warehouseSelected')]
    public function updatedFormWarehouseId($value): void
    {
        $this->form->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->warehouse_id);
    }

    public function render()
    {
        return view('livewire.adjustment.edit');
    }
}
