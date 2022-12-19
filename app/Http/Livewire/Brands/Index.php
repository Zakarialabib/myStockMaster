<?php

declare(strict_types=1);

namespace App\Http\Livewire\Brands;

use App\Imports\BrandsImport;
use App\Models\Brand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Livewire\WithSorting;

class Index extends Component
{
    use WithPagination;
    use LivewireAlert;
    use WithFileUploads;
    use WithSorting;

    /** @var mixed */
    public $brand;

    /** @var string[] */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal', 'editModal', 'importModal',
    ];

    public int $perPage;

    public $image;

    public $file;

    public $refreshIndex;

    /** @var bool */
    public $showModal = false;

    /** @var bool */
    public $importModal = false;

    /** @var bool */
    public $editModal = false;

    public $selectPage = false;
    /** @var array */
    public array $orderable;

    /** @var string */
    public string $search = '';

    /** @var array */
    public $selected = [];

    /** @var array */
    public array $paginationOptions;

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

    public array $rules = [
        'brand.name'        => ['required', 'string', 'max:255'],
        'brand.description' => ['nullable', 'string'],
    ];

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Brand())->orderable;
    }

    public function render(): View|Factory
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

    public function editModal(Brand $brand): void
    {
        abort_if(Gate::denies('brand_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->brand = Brand::find($brand->id);

        $this->editModal = true;
    }

    public function update(): void
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
