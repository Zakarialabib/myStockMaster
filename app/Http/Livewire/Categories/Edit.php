<?php

declare(strict_types=1);

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['editModal'];

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $category;
    public $name;
    public $code;

    /** @var array */
    public $rules = [
        'name' => 'required|min:3|max:255',
        'code' => 'required',
    ];

    public function render()
    {
        return view('livewire.categories.edit');
    }

    public function editModal($id): void
    {
        abort_if(Gate::denies('category_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::findOrFail($id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->category->save();

        $this->emit('refreshIndex');

        $this->editModal = false;

        $this->alert('success', __('Category updated successfully.'));
    }
}
