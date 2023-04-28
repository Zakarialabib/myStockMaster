<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createSupplier'];

    /** @var bool */
    public $createSupplier = false;

    /** @var mixed */
    public $supplier;

    /** @var array */
    protected $rules = [
        'supplier.name'       => 'required|string|min:3|max:255',
        'supplier.phone'      => 'required|numeric',
        'supplier.email'      => 'nullable|email|max:255',
        'supplier.address'    => 'nullable|string|max:255',
        'supplier.city'       => 'nullable|string|max:255',
        'supplier.country'    => 'nullable|string|max:255',
        'supplier.tax_number' => 'nullable|numeric|max:255',
    ];

    protected $messages = [
        'supplier.name.required'  => 'The name field cannot be empty.',
        'supplier.phone.required' => 'The phone field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
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

        $this->supplier = new Supplier();

        $this->createSupplier = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            $this->supplier->create($validatedData);

            $this->alert('success', __('Supplier created successfully.'));

            $this->emit('refreshIndex');

            $this->createSupplier = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Supplier was not created .').$th->getMessage());
        }
    }
}
