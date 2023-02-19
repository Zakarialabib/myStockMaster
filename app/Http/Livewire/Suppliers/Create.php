<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['createSupplier'];

    /** @var bool */
    public $createSupplier = false;

    /** @var mixed */
    public $supplier;

    /** @var array */
    protected $rules = [
        'supplier.name'       => 'required|string|max:255',
        'supplier.phone'      => 'required|numeric',
        'supplier.email'      => 'nullable|email|max:255',
        'supplier.address'    => 'nullable|string|max:255',
        'supplier.city'       => 'nullable|string|max:255',
        'supplier.country'    => 'nullable|string|max:255',
        'supplier.tax_number' => 'nullable|numeric|max:255',
    ];

    public function mount(Supplier $supplier): void
    {
        $this->supplier = $supplier;
    }

    public function render()
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
