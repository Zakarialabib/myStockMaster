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
    public $listeners = ['createModal'];

    /** @var bool */
    public $createModal = false;

    /** @var mixed */
    public $supplier;

    /** @var array */
    protected $rules = [
        'supplier.name'       => 'required|string|min:3|max:255',
        'supplier.phone'      => 'required|string',
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

    public function render()
    {
        abort_if(Gate::denies('supplier_create'), 403);

        return view('livewire.suppliers.create');
    }

    public function createModal()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->supplier = new Supplier();

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $this->validate();

            $this->supplier->save();

            $this->alert('success', __('Supplier created successfully.'));

            $this->emit('refreshIndex');

            $this->reset('supplier');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Supplier was not created .').$th->getMessage());
        }
    }
}
