<?php

namespace App\Http\Livewire\Brands;

use App\Http\Livewire\WithSorting;
use App\Imports\BrandsImport;
use App\Models\Brand;
use App\Support\HasAdvancedFilter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
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
    use HasAdvancedFilter;
    use WithFileUploads;

    public $brand;

    public $listeners = [
        'confirmDelete', 'delete', 'refreshIndex',
        'showModal', 'editModal', 'importModal',
    ];

    public int $perPage;
    
    public $image;
    
    public $refreshIndex;

    /** @var boolean */
    public $showModal = false;

    /** @var boolean */
    public $importModal = false;
    
    /** @var boolean */
    public $editModal = false;

    public int $selectPage;

    public array $orderable;

    public string $search = '';

    /** @var array */
    public $selected = [];

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
        'brand.name' => ['required', 'string', 'max:255'],
        'brand.description' => ['nullable', 'string'],
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
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Brand)->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('brand_access'), 403);

        $query = Brand::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $brands = $query->paginate($this->perPage);

        return view('livewire.brands.index', compact('brands'));
    }

    public function editModal(Brand $brand)
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = Brand::find($brand->id);

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->validate();
        // upload image if it does or doesn't exist

        if ($this->image) {
            $imageName = Str::slug($this->brand->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
            $this->image->storeAs('brands', $imageName);
            $this->brand->image = $imageName;
        }

        $this->brand->save();

        $this->editModal = false;

        $this->alert('success', __('Brand updated successfully.'));
    }

    public function showModal(Brand $brand)
    {
        abort_if(Gate::denies('brand_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = Brand::find($brand->id);

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('brand_delete'), 403);

        Brand::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Brand $brand)
    {
        abort_if(Gate::denies('brand_delete'), 403);

        $brand->delete();

        $this->alert('success', __('Brand deleted successfully.'));
    }

    public function importModal()
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->importModal = true;
    }

    public function import()
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new BrandsImport, $this->file);

        $this->alert('success', __('Brand imported successfully.'));
    }
}
