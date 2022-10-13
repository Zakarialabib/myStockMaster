<?php

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use LivewireAlert , WithFileUploads;

    public $createBrand;
    
    public $image;

    public $listeners = ['createBrand'];

    public function mount(Brand $brand)
    {
        $this->brand = $brand;
    }

    public array $rules = [
        'brand.name' => ['required', 'string', 'max:255'],
        'brand.description' => ['nullable', 'string'],
    ];

    public function render()
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.create');
    }

    public function createBrand()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createBrand = true;
    }

    public function create()
    {
        $this->validate();

        if($this->image){
            $imageName = Str::slug($this->brand->name).'.'.$this->image->extension();
            $this->image->storeAs('brands',$imageName);
            $this->brand->image = $imageName;
        }

        $this->brand->save();

        $this->emit('refreshIndex');
        
        $this->alert('success', 'Brand created successfully.');
        
        $this->createBrand = false;
    }
}
