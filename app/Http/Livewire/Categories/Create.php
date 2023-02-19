<?php

declare(strict_types=1);

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createCategory'];

    /** @var bool */
    public $createCategory = false;

    /** @var mixed */
    public $category;

    /** @var array */
    protected $rules = [
        'category.name' => 'required|min:3|max:255',
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
        abort_if(Gate::denies('category_access'), 403);

        return view('livewire.categories.create');
    }

    public function createCategory(): void
    {
        abort_if(Gate::denies('category_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = new Category();

        $this->createCategory = true;
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        $this->category->save($validatedData);

        $this->emit('refreshIndex');

        $this->alert('success', __('Category created successfully.'));

        $this->createCategory = false;
    }
}
