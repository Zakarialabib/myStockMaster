<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Category as CategoryModel;

class Category extends Component
{
    use WithPagination, WithSorting, LivewireAlert, WithFileUploads;

    public $category;
    public $category_code;
    public $category_name;

    public $listeners = ['show', 'confirmDelete', 'delete','createModal','showModal','editModal'];

    public int $perPage;

    public $show;
    
    public $showModal;

    public $createModal;

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
        'category.category_code' => '',
        'category.category_name' => 'required',
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

    public function createModal()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;

    }

    public function create()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->validate();

        CategoryModel::create($this->category);

        $this->createModal = false;

        $this->alert('success', 'Category created successfully.');
    }

    public function editModal(CategoryModel $category)
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

    // Show modal
    
    public function showModal(CategoryModel $category)
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

        CategoryModel::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }
    
    public function delete(CategoryModel $category)
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        if ($category->products()->isNotEmpty()) {
            return back()->withErrors('Can\'t delete beacuse there are products associated with this category.');
        } else {
            $category->delete();

            $this->alert('success', 'Category deleted successfully.');
        }
    }


}

