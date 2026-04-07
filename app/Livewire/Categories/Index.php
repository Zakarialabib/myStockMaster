<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Livewire\Utils\Datatable;
use App\Livewire\Utils\HasDelete;
use App\Models\Category;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Categories')]
class Index extends Component
{
    use Datatable;
    use HasDelete;
    use WithAlert;
    use WithFileUploads;

    /** @var mixed */
    public mixed $category;

    public mixed $file = null;

    public bool $showModal = false;

    public string $model = Category::class;

    public function mount(): void
    {
        $this->mountDatatable();
        $this->sortBy = 'id';
        $this->sortDirection = 'asc';
    }

    public function render(): mixed
    {
        abort_if(Gate::denies('category_access'), 403);

        $query = Category::query()->withCount('products')->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $categories = $query->paginate($this->perPage);

        return view('livewire.categories.index', ['categories' => $categories]);
    }

    public function openShowModal(Category $category): void
    {
        abort_if(Gate::denies('category_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::query()->find($category->id);

        $this->showModal = true;
    }
}
