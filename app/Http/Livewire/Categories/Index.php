<?php

namespace App\Http\Livewire\Categories;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CategoriesImport;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert, WithFileUploads;

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

        $query = Category::advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
                            'order_direction' => $this->sortDirection,
                        ]);

        $categories = $query->paginate($this->perPage);

        return view('livewire.categories.index', compact('categories'));
    }

    public function editModal(Category $category)
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = $category;

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->validate();

        $this->category->save();

        $this->editModal = false;

        $this->alert('success', 'Category updated successfully.');
    }
    
    public function showModal(Category $category)
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = $category;

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

        if ($category->products()->isNotEmpty()) {
            return back()->withErrors('Can\'t delete beacuse there are products associated with this category.');
        } else {
            $category->delete();

            $this->alert('success', 'Category deleted successfully.');
        }
    }

    public function importExcel ()
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

        Excel::import(new CategoriesImport, $file);

        $this->alert('success', 'Categories imported successfully.');

        $this->importModal = false;
    }


}

