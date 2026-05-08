<?php

declare(strict_types=1);

namespace App\Livewire\Brands;

use App\Livewire\Forms\BrandForm;
use App\Models\Brand;
use App\Services\BrandService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $editModal = false;

    public Brand $brand;

    public BrandForm $form;

    #[On('editModal')]
    public function openEditModal(mixed $id): void
    {
        abort_if(Gate::denies('brand_update'), 403);

        $this->resetErrorBag();

        $this->form->reset();

        $this->brand = Brand::query()->where('id', $id)->firstOrFail();

        $this->form->name = $this->brand->name;
        $this->form->description = $this->brand->description;
        $this->form->image = $this->brand->image;
        $this->form->origin = $this->brand->origin ?? '';

        $this->editModal = true;
    }

    public function update(BrandService $brandService): void
    {
        $this->form->validate();

        if (Brand::query()->where('name', $this->form->name)->whereKeyNot($this->brand->id)->exists()) {
            throw ValidationException::withMessages([
                'form.name' => __('The brand name has already been taken.'),
            ]);
        }

        $brandService->update($this->brand, $this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Brand updated successfully.'));

        $this->form->reset();

        $this->editModal = false;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.brands.edit');
    }
}
