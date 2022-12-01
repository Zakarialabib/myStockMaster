<?php

namespace App\Http\Livewire\Categories;

use App\Http\Livewire\WithSorting;
use App\Imports\CategoriesImport;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use WithFileUploads;

    public $category;

    /** @var boolean */
    public $name;

    public $listeners = [
        'confirmDelete', 'delete', 'importModal',
        'refreshIndex', 'showModal', 'editModal',
    ];

    public int $perPage;

    public $refreshIndex;

    /** @var boolean */
    public $showModal = false;

    /** @var boolean */
    public $importModal = false;

    /** @var boolean */
    public $editModal = false;

    /** @var array */
    public $orderable;

    /** @var string */
    public $search = '';

    /** @var array */
    public $selected = [];

    /** @var array */
    public $paginationOptions;

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

    protected function rules():array
    {
        return
            [
                'category.name' => 'required|string|max:255',
            ];
    }

    public function getSelectedCountProperty():int
    {
        return count($this->selected);
    }

    public function updatingSearch():void
    {
        $this->resetPage();
    }

    public function updatingPerPage():void
    {
        $this->resetPage();
    }

    public function resetSelected():void
    {
        $this->selected = [];
    }

    public function refreshIndex():void
    {
        $this->resetPage();
    }

    public function mount():void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Category)->orderable;
    }

    public function render():mixed
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $query = Category::with('products')->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $categories = $query->paginate($this->perPage);

        return view('livewire.categories.index', compact('categories'))->extends('layouts.app');
    }

    public function editModal(Category $category):void
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::find($category->id);

        $this->editModal = true;
    }

    public function update():void
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->validate();

        $this->category->save();

        $this->editModal = false;

        $this->alert('success', __('Category updated successfully.'));
    }

    public function showModal(Category $category):void
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::find($category->id);

        $this->showModal = true;
    }

    public function deleteSelected():void
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        Category::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Category $category):void
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        if ($category->products->count() > 0) {
            $this->alert('error', __('Category has products.'));
        } else {
            $category->delete();
            $this->alert('success', __('Category deleted successfully.'));
        }
    }

    public function importModal():void
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->importModal = true;
    }

    public function import():void
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        $file = $this->file('file');

        Excel::import(new CategoriesImport, $file);

        $this->alert('success', __('Categories imported successfully.'));

        $this->importModal = false;
    }
}
