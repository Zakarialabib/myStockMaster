<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Category as CategoryModel;

class Category extends Component
{
    use WithPagination, WithSorting, WithConfirmation, WithFileUploads;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;
    
    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new CategoryModel())->orderable;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        CategoryModel::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(CategoryModel $category)
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $category->delete();
    }

    public function render()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $query = CategoryModel::advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
                            'order_direction' => $this->sortDirection,
                        ]);

        $categories = $query->paginate($this->perPage);

        return view('livewire.products.category', compact('categories'));
    }

}

