<?php

declare(strict_types=1);

namespace App\Livewire\Brands;

use App\Livewire\Forms\BrandForm;
use App\Models\Brand;
use App\Services\BrandService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithAlert, WithFileUploads;

    public bool $editModal = false;

    public Brand $brand;

    public BrandForm $form;

    #[On('editModal')]
    public function openEditModal($id): void
    {
        abort_if(Gate::denies('brand_update'), 403);

        $this->resetErrorBag();

        $this->form->reset();

        $this->brand = Brand::where('id', $id)->firstOrFail();

        $this->form->name = $this->brand->name;
        $this->form->description = $this->brand->description;
        $this->form->image = $this->brand->image;
        $this->form->origin = $this->brand->origin ?? '';

        $this->editModal = true;
    }

    public function update(BrandService $service): void
    {
        $this->form->validate();

        $service->update($this->brand, $this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Brand updated successfully.'));

        $this->form->reset();

        $this->editModal = false;
    }

    public function render()
    {
        return view('livewire.brands.edit');
    }
}
