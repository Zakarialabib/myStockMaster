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
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $editModal = false;

    public Brand $brand;

    public BrandForm $form;
    public $preview;

    #[On('editModal')]
    public function openEditModal(mixed $id): void
    {
        abort_if(Gate::denies('brand_update'), 403);

        $this->resetErrorBag();

        $this->form->reset();

        $this->brand = Brand::query()->where('id', $id)->firstOrFail();

        $this->form->name = $this->brand->name;
        $this->form->description = $this->brand->description;
        $path = 'brands/' . $this->brand->image;
        $this->preview = $this->generatepreview($path);
        $this->form->origin = $this->brand->origin ?? '';

        $this->editModal = true;
    }

    public function update(BrandService $brandService): void
    {
        $this->form->validate();

        $brand = $brandService->update($this->brand, $this->form->all());

        $path = 'brands/' . $brand->image;
        $this->preview = $this->generatepreview($path);
        
        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Brand updated successfully.'));

        $this->form->reset();

        $this->editModal = false;
    }

    private function generatepreview($path)
    {
        return [
            'url' => Storage::disk('local_files')->url($path),
            'extension' => pathinfo($path, PATHINFO_EXTENSION),
            'size' => Storage::disk('local_files')->size($path),
        ];
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.brands.edit');
    }
}
