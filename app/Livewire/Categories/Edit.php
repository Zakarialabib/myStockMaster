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

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $category;

    #[Validate('required', message: 'Please provide a name')]
    #[Validate('min:3', message: 'This name is too short')]
    public string $name;

    public $description;

    public $code;

    public $image;

    public function render()
    {
        return view('livewire.categories.edit');
    }

    #[On('editModal')]
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

        if ($this->image) {
            $imageName = Str::slug($this->name).'-'.Str::random(3).'.'.$this->image->extension();
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
