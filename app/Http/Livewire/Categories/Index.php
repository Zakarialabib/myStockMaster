<?php

declare(strict_types=1);

namespace App\Http\Livewire\Categories;

use App\Http\Livewire\WithSorting;
use App\Imports\CategoriesImport;
use App\Models\Category;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use WithFileUploads;
    use Datatable;

    /** @var mixed */
    public $category;

    public $file;

    /** @var array<string> */
    public $listeners = [
        'importModal', 'showModal',
        'refreshIndex' => '$refresh',
        'delete',
    ];

    /** @var bool */
    public $showModal = false;

    /** @var bool */
    public $importModal = false;

    /** @var bool */
    public $deleteModal = false;

    /** @var array<array<string>> */
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

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Category())->orderable;
    }

    public function render(): mixed
    {
        abort_if(Gate::denies('category_access'), 403);

        $query = Category::with('products')->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $categories = $query->paginate($this->perPage);

        return view('livewire.categories.index', compact('categories'));
    }

    public function showModal(Category $category): void
    {
        abort_if(Gate::denies('category_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->category = Category::find($category->id);

        $this->showModal = true;
    }

    public function confirmed()
    {
        $this->emit('delete');
    }

    public function deleteModal($category)
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);
        $this->category = $category;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('category_delete'), 403);

        Category::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(): void
    {
        abort_if(Gate::denies('category_delete'), 403);
        $category = Category::findOrFail($this->category);
        
        if ($category->products->count() > 0) {
            $this->alert('error', __('Category has products.'));
        } else {
            Category::findOrFail($this->category)->delete();
            $this->alert('success', __('Category deleted successfully.'));
        }
    }

    public function importModal(): void
    {
        abort_if(Gate::denies('category_access'), 403);

        $this->importModal = true;
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('categories_import_sample.xls');
    }

    public function import(): void
    {
        abort_if(Gate::denies('category_access'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        $file = $this->file('file');

        Excel::import(new CategoriesImport(), $file);

        $this->alert('success', __('Categories imported successfully.'));

        $this->importModal = false;
    }
}
