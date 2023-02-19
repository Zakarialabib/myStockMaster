<?php

declare(strict_types=1);

namespace App\Http\Livewire\Brands;

use App\Imports\BrandsImport;
use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Livewire\WithSorting;
use App\Traits\Datatable;

class Index extends Component
{
    use WithPagination;
    use LivewireAlert;
    use WithFileUploads;
    use WithSorting;
    use Datatable;

    /** @var mixed */
    public $brand;

    public $brandIds;

    /** @var string[] */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal', 'importModal',
        'delete',
    ];

    public $image;

    public $file;

    /** @var bool */
    public $showModal = false;

    /** @var bool */
    public $importModal = false;

    public $selectAll;

    /** @var string[][] */
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

    public function selectAll()
    {
        if (count(array_intersect($this->selected, Brand::pluck('id')->toArray())) == count(Brand::pluck('id')->toArray())) {
            $this->selected = [];
        } else {
            $this->selected = Brand::pluck('id')->toArray();
        }
    }

    public function selectPage()
    {
        if (count(array_intersect($this->selected, Brand::paginate($this->perPage)->pluck('id')->toArray())) == count(Brand::paginate($this->perPage)->pluck('id')->toArray())) {
            $this->selected = [];
        } else {
            $this->selected = $this->brandIds;
        }
    }

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Brand())->orderable;
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

    public function showModal(Brand $brand): void
    {
        abort_if(Gate::denies('brand_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = Brand::find($brand->id);

        $this->showModal = true;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('brand_delete'), 403);

        Brand::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Brand $brand): void
    {
        abort_if(Gate::denies('brand_delete'), 403);

        $brand->delete();

        $this->alert('success', __('Brand deleted successfully.'));
    }

    public function importModal(): void
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->importModal = true;
    }

    public function import(): void
    {
        abort_if(Gate::denies('brand_create'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new BrandsImport(), $this->file);

        $this->alert('success', __('Brand imported successfully.'));
    }
}
