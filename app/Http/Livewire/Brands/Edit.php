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

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    /** @var mixed */
    public $brand;

    public $image;

    /** @var string[] */
    public $listeners = ['editModal'];

    public array $rules = [
        'brand.name'        => ['required', 'string', 'max:255'],
        'brand.description' => ['nullable', 'string'],
    ];

    public function render(): View|Factory
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.edit');
    }

    public function editModal($id): void
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = Brand::findOrFail($id);

        $this->editModal = true;
    }

    public function update(): void
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->validate();
        // upload image if it does or doesn't exist

        if ($this->image) {
            $imageName = Str::slug($this->brand->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
            $this->image->storeAs('brands', $imageName);
            $this->brand->image = $imageName;
        }

        $this->brand->save();

        $this->emit('refreshIndex');

        $this->alert('success', __('Brand updated successfully.'));

        $this->editModal = false;
    }
}
