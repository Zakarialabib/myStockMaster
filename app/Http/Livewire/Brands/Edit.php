<?php

declare(strict_types=1);

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $brand;

    public $image;

    public $name;

    public $description;

    /** @var string[] */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'name'        => 'required|string||min:3|max:255',
        'description' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'The name field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.brands.edit');
    }

    public function editModal($id): void
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = Brand::where('id', $id)->firstOrFail();

        $this->name = $this->brand->name;
        $this->description = $this->brand->description;

        $this->editModal = true;
    }

    public function update(): void
    {
        $validatedData = $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
            $this->image->storeAs('brands', $imageName);
            $this->brand->image = $imageName;
        }

        $this->update($validatedData);

        $this->emit('refreshIndex');

        $this->alert('success', __('Brand updated successfully.'));

        $this->editModal = false;
    }
}
