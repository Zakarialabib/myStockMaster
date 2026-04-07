<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Livewire\Forms\TransferForm;
use App\Livewire\Utils\WithModels;
use App\Services\TransferService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithAlert;
    use WithModels;

    public TransferForm $form;

    public $products;

    public $hasTransfers;

    public function mount(): void
    {
        $this->products = [];

        $this->form->reference = 'TR-' . Str::random(5);
        $this->form->date = date('Y-m-d');
        $this->form->status = 1;

        if (settings()->default_warehouse_id !== null) {
            $this->form->from_warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render()
    {
        return view('livewire.transfer.create');
    }

    public function updatedFormFromWarehouseId($value): void
    {
        $this->form->from_warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->from_warehouse_id);
    }

    public function store(TransferService $transferService)
    {
        abort_if(Gate::denies('transfer_create'), 403);

        if (! $this->form->from_warehouse_id) {
            $this->alert('error', __('Please select a from warehouse'));

            return;
        }

        if (! $this->form->to_warehouse_id) {
            $this->alert('error', __('Please select a to warehouse'));

            return;
        }

        if ($this->form->from_warehouse_id === $this->form->to_warehouse_id) {
            $this->alert('error', __('From warehouse and To warehouse cannot be the same'));

            return;
        }

        try {
            $this->form->validate();

            $transferService->createTransfer($this->form->all(), $this->products);

            $this->alert('success', __('Transfer created successfully'));

            return redirect()->route('transfers.index')->navigate();
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error Occurred in ' . $throwable->getMessage());
        }
    }

    #[On('productSelected')]
    public function productSelected(array $product): void
    {
        $product['quantities'] = 1;

        if (in_array($product, $this->products)) {
            $this->alert('error', __('Already exists in the product list!'));

            return;
        }

        $this->products[] = $product;
        $this->calculateTotal();
    }

    public function removeProduct($key): void
    {
        unset($this->products[$key]);
        $this->calculateTotal();
    }

    public function updateQuantity($key, $quantity): void
    {
        $this->products[$key]['quantities'] = $quantity;
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->form->total_qty = 0;
        $this->form->total_cost = 0;
        $this->form->total_amount = 0;

        foreach ($this->products as $product) {
            $qty = $product['quantities'] ?? 1;
            $price = $product['price'] ?? 0;
            $cost = $product['cost'] ?? 0;

            $this->form->total_qty += $qty;
            $this->form->total_cost += $qty * $cost;
            $this->form->total_amount += $qty * $price;
        }
    }
}
