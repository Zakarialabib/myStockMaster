<?php

declare(strict_types=1);

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
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
    public $brand;

    public $image;

    /** @var array<string> */
    public $listeners = ['editModal'];

    /** @var array */
    protected $rules = [
        'brand.name'        => 'required|string||min:3|max:255',
        'brand.description' => 'nullable|string',
    ];

    protected $messages = [
        'brand.name.required' => 'The name field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function editModal($id): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = Brand::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update()
    {
        $validatedData = $this->validate();

        try {
            if ($this->image) {
                $imageName = Str::slug($this->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
                $this->image->storeAs('brands', $imageName);
                $this->image = $imageName;
            }

            $this->brand->save($validatedData);

            $this->emit('refreshIndex');

            $this->alert('success', __('Brand updated successfully.'));

            $this->editModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Error.').$th->getMessage());
        }
    }

    public function render()
    {
        abort_if(Gate::denies('brand_update'), 403);

        return view('livewire.brands.edit');
    }
}
