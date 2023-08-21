<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $supplier;

    /** @var array<string> */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'supplier.name'       => 'required|string|min:3|max:255',
        'supplier.email'      => 'nullable|max:255',
        'supplier.phone'      => 'required|numeric',
        'supplier.city'       => 'nullable|max:255',
        'supplier.country'    => 'nullable|max:255',
        'supplier.address'    => 'nullable|max:255',
        'supplier.tax_number' => 'nullable||max:255',
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
        abort_if(Gate::denies('supplier_update'), 403);

        return view('livewire.suppliers.edit');
    }

    public function editModal($id)
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->supplier = Supplier::findOrFail($id);

        $this->editModal = true;
    }

    public function update(): void
    {
        try {
            $validatedData = $this->validate();

            $this->supplier->save($validatedData);

            $this->alert('success', __('Supplier updated successfully.'));

            $this->emit('refreshIndex');

            $this->editModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Error.').$th->getMessage());
        }
    }
}
