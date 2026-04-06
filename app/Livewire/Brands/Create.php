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

class Create extends Component
{
    use WithAlert, WithFileUploads;

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

    public function create(BrandService $service): void
    {
        $this->form->validate();

        $service->create($this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Brand created successfully.'));

        $this->form->reset();

        $this->createModal = false;
    }

    public function render()
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.create');
    }
}
