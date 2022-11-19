<?php

namespace App\Http\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createSupplier'];
    
    public $createSupplier; 

    public array $rules = [
        'supplier.name' => ['required', 'string', 'max:255'],
        'supplier.email' => ['nullable', 'string', 'max:255'],
        'supplier.phone' => ['required'],
        'supplier.address' => ['nullable', 'string', 'max:255'],
        'supplier.city' => ['nullable', 'string', 'max:255'],
        'supplier.country' => ['nullable', 'string', 'max:255'],
        'supplier.tax_number' => ['nullable', 'string', 'max:255'],
    ];

    public function mount(Supplier $supplier)
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

    public function create()
    {
        $this->validate();

        $this->supplier->save();

        $this->alert('success', __('Supplier created successfully.'));

        $this->emit('refreshIndex');

        $this->createSupplier = false;
        
    }
}
