<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Models\Category;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $editModal = false;

    public Category $category;

    #[Validate('required', message: 'Please provide a name')]
    #[Validate('min:3', message: 'This name is too short')]
    public string $name;

    public ?string $description = null;

    public ?string $code = null;

    public $image;

    public function render()
    {
        return view('livewire.categories.edit');
    }

    #[On('openEditModal')]
    public function openModal($id): void
    {
        abort_if(Gate::denies('category_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::where('id', $id)->firstOrFail();
        $this->name = $this->category->name;
        $this->description = $this->category->description;
        $this->code = $this->category->code;
        $this->image = $this->category->image ?? null;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        if ($this->image && ! is_string($this->image)) {
            // Call to a member function extension() on string
            $imageName = Str::slug($this->name) . '-' . Str::random(3) . '.' . $this->image->extension();
            $this->image->storeAs('categories', $imageName);
            $this->image = $imageName;
        }

        $this->category->update($this->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Category updated successfully.'));

        $this->reset(['name', 'description', 'code', 'image']);

        $this->editModal = false;
    }
}
