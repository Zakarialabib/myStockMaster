<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['createSupplier'];

    /** @var bool */
    public $createSupplier = false;

    /** @var mixed */
    public $supplier;

    public array $rules = [
        'supplier.name'       => ['required', 'string', 'max:255'],
        'supplier.email'      => ['nullable', 'string', 'max:255'],
        'supplier.phone'      => ['required'],
        'supplier.address'    => ['nullable', 'string', 'max:255'],
        'supplier.city'       => ['nullable', 'string', 'max:255'],
        'supplier.country'    => ['nullable', 'string', 'max:255'],
        'supplier.tax_number' => ['nullable', 'string', 'max:255'],
    ];

    public function mount(Supplier $supplier): void
    {
        $this->supplier = $supplier;
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('supplier_create'), 403);

        return view('livewire.suppliers.create');
    }

    public function createSupplier()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createSupplier = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->supplier->save();

        $this->alert('success', __('Supplier created successfully.'));

        $this->emit('refreshIndex');

        $this->createSupplier = false;
    }
}
