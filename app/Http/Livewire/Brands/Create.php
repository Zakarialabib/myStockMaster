<?php

declare(strict_types=1);

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $createBrand = false;

    public $brand;

    public $image;

    public $listeners = ['createBrand'];

    public array $rules = [
        'brand.name'        => ['required', 'string', 'max:255'],
        'brand.description' => ['nullable', 'string'],
    ];

    public function mount(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.create');
    }

    public function createBrand(): void
    {
        // strange behavior with reset()

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createBrand = true;
    }

    public function create(): void
    {
        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->brand->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
            $this->image->storeAs('brands', $imageName);
            $this->brand->image = $imageName;
        }

        $this->brand->save();

        $this->emit('refreshIndex');

        $this->alert('success', __('Brand created successfully.'));

        $this->createBrand = false;
    }
}
