<?php

namespace App\Http\Livewire\Brands;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use App\Models\Brand;
use App\Support\HasAdvancedFilter;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination, WithSorting,
         LivewireAlert, HasAdvancedFilter, WithFileUploads;

    public $brand;

    public $listeners = ['show', 'confirmDelete', 'delete','refreshIndex','showModal','editModal'];

    public int $perPage;

    public $show;
    
    public $image;

    public $showModal;

    public $refreshIndex;

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
        $this->orderable         = (new Brand())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('brand_access'), 403);

        $query = Brand::advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
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

        $this->brand = $brand;

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->validate();

        if ($this->brand->image != null) {    
            $imageName = Str::slug($this->brand->name).'.'.$this->image->extension();
            $this->image->storeAs('brands',$imageName);
            $this->brand->image = $imageName;
        }

        $this->brand->save();

        $this->editModal = false;

        $this->alert('success', 'Brand updated successfully.');
    }
    
    public function showModal(Brand $brand)
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

        Brand::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }
    
    public function delete(Brand $brand)
    {
        abort_if(Gate::denies('brand_delete'), 403);
       
        $brand->delete();

        $this->alert('success', 'Brand deleted successfully.');

    }


}

