<?php

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $createBrand;

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

        $this->brand->save();

        $this->emit('refreshIndex');
        
        $this->alert('success', 'Brand created successfully.');
        
        $this->createBrand = false;
    }
}
