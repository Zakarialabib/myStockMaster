<?php

namespace App\Http\Livewire\Categories;

use Livewire\{Component, WithFileUploads, WithPagination};
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Livewire\WithSorting;
use App\Models\Category;
use App\Imports\CategoriesImport;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use WithFileUploads;

    public $category;
    public $code;
    public $name;

    public $listeners = [
        'show', 'confirmDelete', 'delete',
        'refreshIndex','showModal','editModal',
        'importModal'
    ];

    public int $perPage;

    public $show;

    public $showModal;

    public $refreshIndex;

    public $importModal;

    public $editModal;

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

    public array $rules = [
        'category.code' => '',
        'category.name' => 'required',
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

    public function refreshIndex()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Category())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $query = Category::with('products')->advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
                            'order_direction' => $this->sortDirection,
                        ]);

        $categories = $query->paginate($this->perPage);

        return view('livewire.categories.index', compact('categories'))->extends('layouts.app');
    }

    public function editModal(Category $category)
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::find($category->id);

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->validate();

        $this->category->save();

        $this->editModal = false;

        $this->alert('success', __('Category updated successfully.'));
    }

    public function showModal(Category $category)
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::find($category->id);

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        Category::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Category $category)
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        if ($category->products->count() > 0) {
            $this->alert('error', __('Category has products.'));
        } else {
            $category->delete();
            $this->alert('success', __('Category deleted successfully.'));
        }
    }

    public function importModal()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->importModal = true;
    }

    public function import()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt'
        ]);

        $file = $this->file('file');

        Excel::import(new CategoriesImport(), $file);

        $this->alert('success', __('Categories imported successfully.'));

        $this->importModal = false;
    }
}
