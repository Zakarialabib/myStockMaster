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

    public $listeners = ['createCategory'];

    /** @var bool */
    public $createCategory = false;

    public $category;

    /** @var string */
    public $name;

    protected function rules(): array
    {
        return ['name' => 'required|string|max:255', ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        return view('livewire.categories.create');
    }

    public function createCategory(): void
    {
        $this->reset();

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
