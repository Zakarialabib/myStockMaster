<?php

declare(strict_types=1);

namespace App\Http\Livewire\Warehouses;

use App\Http\Livewire\WithSorting;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;

    /** @var mixed */
    public $warehouse;

    public int $perPage;

    /** @var string[] */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal', 'editModal', ];

    /** @var bool */
    public $showModal = false;

    /** @var bool */
    public $importModal = false;

    /** @var bool */
    public $editModal = false;
    /** @var array */
    public array $orderable;

    /** @var string */
    public string $search = '';

    /** @var array */
    public array $selected = [];

    /** @var array */
    public array $paginationOptions;

    public $refreshIndex;

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

    public array $rules = [
        'warehouse.name'  => ['string', 'required'],
        'warehouse.phone' => ['string', 'nullable'],
        'warehouse.city'  => ['string', 'nullable'],
        'warehouse.email' => ['string', 'nullable'],
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Warehouse())->orderable;
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('warehouse_access'), 403);

        $query = Warehouse::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $warehouses = $query->paginate($this->perPage);

        return view('livewire.warehouses.index', compact('warehouses'));
    }

    public function showModal(Warehouse $warehouse)
    {
        abort_if(Gate::denies('warehouse_show'), 403);

        $this->warehouse = Warehouse::find($warehouse->id);

        $this->showModal = true;
    }

    public function editModal(Warehouse $warehouse)
    {
        abort_if(Gate::denies('warehouse_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->warehouse = Warehouse::find($warehouse->id);

        $this->editModal = true;
    }

    public function update(): void
    {
        abort_if(Gate::denies('warehouse_edit'), 403);

        $this->validate();

        $this->warehouse->save();

        $this->editModal = false;

        $this->alert('success', __('Warehouse updated successfully'));
    }

    public function delete(Warehouse $warehouse)
    {
        abort_if(Gate::denies('warehouse_delete'), 403);

        $warehouse->delete();

        $this->alert('warning', __('Warehouse successfully deleted.'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('warehouse_delete'), 403);

        Warehouse::whereIn('id', $this->selected)->delete();

        $this->alert('warning', __('Warehouses successfully deleted.'));
    }
}
