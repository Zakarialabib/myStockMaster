<?php

declare(strict_types=1);

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['editModal'];

    /** @var bool */
    public $editModal = false;

    /** @var mixed */
    public $category;

    /** @var array */
    protected $rules = [
        'category.name' => 'required|min:3|max:255',
        'category.code' => 'required|max:255',
    ];

    protected $messages = [
        'category.name.required' => 'The name field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.categories.edit');
    }

    public function editModal($id): void
    {
        abort_if(Gate::denies('category_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::where('id', $id)->firstOrFail();

        $this->editModal = true;
    }

    public function update(): void
    {
        try {
            $validatedData = $this->validate();

            $this->category->save($validatedData);

            $this->emit('refreshIndex');

            $this->editModal = false;

            $this->alert('success', __('Category updated successfully.'));
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }
}
