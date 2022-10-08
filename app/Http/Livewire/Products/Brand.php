<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use App\Models\Brand as BrandModel;
use App\Support\HasAdvancedFilter;

class Brand extends Component
{
    use WithPagination, WithSorting, LivewireAlert, HasAdvancedFilter;

    public $brand;

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

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new BrandModel())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('brand_access'), 403);

        $query = BrandModel::advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
                            'order_direction' => $this->sortDirection,
                        ]);

        $brands = $query->paginate($this->perPage);

        return view('livewire.products.brand', compact('brands'));
    }

    public function createModal()
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;

    }

    public function create()
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->validate();

        BrandModel::create($this->brand);

        $this->createModal = false;

        $this->alert('success', 'Brand created successfully.');
    }

    public function editModal(BrandModel $brand)
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = $brand;

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->validate();

        $this->brand->save();

        $this->editModal = false;

        $this->alert('success', 'Brand updated successfully.');
    }

    // Show modal
    
    public function showModal(BrandModel $brand)
    {
        abort_if(Gate::denies('brand_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = $brand;

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('brand_delete'), 403);

        BrandModel::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }
    
    public function delete(BrandModel $brand)
    {
        abort_if(Gate::denies('brand_delete'), 403);
       
        $brand->delete();

        $this->alert('success', 'Brand deleted successfully.');

    }


}

