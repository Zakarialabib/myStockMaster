<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $editModal = false;

    public Category $category;

    public CategoryForm $form;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.categories.edit');
    }

    #[On('openEditModal')]
    public function openModal(int|string $id): void
    {
        abort_if(Gate::denies('category_update'), 403);

        $this->resetErrorBag();

        $this->form->reset();

        $this->category = Category::query()->where('id', $id)->firstOrFail();

        $this->form->name = $this->category->name;
        $this->form->description = $this->category->description;
        $this->form->code = $this->category->code;
        $this->form->image = $this->category->image ?? null;

        $this->editModal = true;
    }

    public function update(CategoryService $categoryService): void
    {
        $this->form->validate();

        $categoryService->update($this->category, $this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Category updated successfully.'));

        $this->form->reset();

        $this->editModal = false;
    }
}
