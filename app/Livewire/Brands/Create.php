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

class Create extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $createModal = false;

    public BrandForm $form;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->resetErrorBag();

        $this->form->reset();

        $this->createModal = true;
    }

    public function create(BrandService $brandService): void
    {
        $this->form->validate();

        if (Brand::query()->where('name', $this->form->name)->exists()) {
            throw ValidationException::withMessages([
                'form.name' => __('The brand name has already been taken.'),
            ]);
        }

        $brandService->create($this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Brand created successfully.'));

        $this->form->reset();

        $this->createModal = false;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.create');
    }
}
