<?php

declare(strict_types=1);

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['createCategory'];

    /** @var bool */
    public $createCategory = false;

    /** @var mixed */
    public $category;

    /** @var string */
    public $name;

    protected array $rules = [
        'name' => 'required|min:3|max:255',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render(): View|Factory
    {
        return view('livewire.categories.create');
    }

    public function createCategory(): void
    {
        abort_if(Gate::denies('category_access'), 403);

        $this->createCategory = true;
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        Category::create($validatedData);

        $this->emit('refreshIndex');

        $this->alert('success', __('Category created successfully.'));

        $this->createCategory = false;
    }
}
