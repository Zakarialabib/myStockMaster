<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $supplier;
    public $name;
    public $email;
    public $phone;
    public $city;
    public $country;
    public $address;
    public $tax_number;

    /** @var string[] */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'name'       => 'required|string|max:255',
        'email'      => 'nullable|max:255',
        'phone'      => 'required|numeric',
        'city'       => 'nullable|max:255',
        'country'    => 'nullable|max:255',
        'address'    => 'nullable|max:255',
        'tax_number' => 'nullable||max:255',
    ];

   
    public function render()
    {
        return view('livewire.suppliers.edit');
    }

    public function editModal($id)
    {
        abort_if(Gate::denies('supplier_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->supplier = Supplier::findOrFail($id);
      
        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        if($this->supplier){
        $this->supplier->save();

        $this->editModal = false;

        $this->alert('success', __('Supplier updated successfully.'));
        }
    }
}
