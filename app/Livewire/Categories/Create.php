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

class Create extends Component
{
    use WithAlert;
    use WithFileUploads;

    public bool $showModal = false;

    public Category $category;

    #[Validate('required', message: 'Please provide a name')]
    #[Validate('min:3', message: 'This name is too short')]
    public string $name;

    public ?string $description = null;

    public $image;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        abort_if(Gate::denies('category_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->showModal = true;
    }

    public function create(): void
    {
        $this->validate();

        if ($this->image && ! is_string($this->image)) {
            // Call to a member function extension() on string
            $imageName = Str::slug($this->name) . '-' . Str::random(3) . '.' . $this->image->extension();
            $this->image->storeAs('categories', $imageName);
            $this->image = $imageName;
        }

        Category::create($this->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Category created successfully.'));

        $this->reset(['name', 'description', 'image']);

        $this->showModal = false;
    }

    public function render()
    {
        abort_if(Gate::denies('category_create'), 403);

        return view('livewire.categories.create');
    }
}
