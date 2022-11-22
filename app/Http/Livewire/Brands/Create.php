<?php

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Livewire\{Component, WithFileUploads};
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Str;

class Create extends Component
{
    use LivewireAlert ;
    use WithFileUploads;

    public $createBrand;

    public $brand;

    public $name;

    public $description;

    public $image;

    public $listeners = ['createBrand'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.create');
    }

    public function createBrand()
    {
        $this->reset();

        $this->createBrand = true;
    }

    public function create()
    {
        $validatedData = $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->brand->name).'.'.$this->image->extension();
            $this->image->storeAs('brands', $imageName);
            $this->brand->image = $imageName;
        }

        Brand::create($validatedData);

        $this->emit('refreshIndex');

        $this->alert('success', __('Brand created successfully.'));

        $this->createBrand = false;
    }
}
