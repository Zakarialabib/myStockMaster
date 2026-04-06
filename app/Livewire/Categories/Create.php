<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Services\CategoryService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $createModal = false;

    public CategoryForm $form;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        abort_if(Gate::denies('category_create'), 403);

        $this->resetErrorBag();

        $this->form->reset();

        $this->createModal = true;
    }

    public function create(CategoryService $service): void
    {
        $this->form->validate();

        $service->create($this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Category created successfully.'));

        $this->form->reset();

        $this->createModal = false;
    }

    public function render()
    {
        abort_if(Gate::denies('category_create'), 403);

        return view('livewire.categories.create');
    }
}
