<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    /** @var bool */
    public $createModal = false;

    public Category $category;

    #[Validate('required', message: 'Please provide a name')]
    #[Validate('min:3', message: 'This name is too short')]
    public string $name;

    public $description;

    public $image;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        abort_if(Gate::denies('category_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->name).'-'.Str::random(3).'.'.$this->image->extension();
            $this->image->storeAs('categories', $imageName);
            $this->image = $imageName;
        }

        Category::create($this->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Category created successfully.'));

        $this->reset(['name', 'description', 'image']);

        $this->createModal = false;
    }

    public function render()
    {
        abort_if(Gate::denies('category_create'), 403);

        return view('livewire.categories.create');
    }
}
