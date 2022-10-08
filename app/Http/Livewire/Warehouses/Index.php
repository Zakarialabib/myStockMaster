<?php

namespace App\Http\Livewire\Warehouses;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Warehouse;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $warehouse;

    public int $perPage;

    public $listeners = ['confirmDelete', 'delete', 'showModal', 'editModal', 'createModal'];

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

    public array $rules = [
        'warehouse.name' => ['string', 'required'],
        'warehouse.address' => ['string', 'nullable'],
        'warehouse.phone' => ['string', 'nullable'],
        'warehouse.email' => ['string', 'nullable'],
        'warehouse.description' => ['string', 'nullable'],
        'warehouse.status' => ['boolean', 'nullable'],
    ];

    public function mount()
    {
        $this->perPage = config('project.per_page');
        $this->paginationOptions = config('project.pagination_options');
        $this->orderable = (new Warehouse())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('warehouse_access'), 403);

        $query = Warehouse::with(['created_by', 'updated_by'])
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDirection);

        $warehouses = $query->paginate($this->perPage);

        return view('livewire.warehouses.index', compact('query', 'warehouses', 'warehouses'));
    }

    public function showModal(Warehouse $warehouse)
    {
        abort_if(Gate::denies('warehouse_show'), 403);

        $this->warehouse = $warehouse;

        $this->showModal = true;
    }
    
    public function editModal(Warehouse $warehouse)
    {
        abort_if(Gate::denies('warehouse_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->warehouse = $warehouse;

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('warehouse_edit'), 403);

        $this->validate();

        $this->warehouse->save();

        $this->editModal = false;

        $this->alert('success', 'Warehouse updated successfully');
    }


    public function createModal()
    {
        abort_if(Gate::denies('warehouse_create'), 403);

        $this->createModal = true;
    }

    public function create()
    {
        abort_if(Gate::denies('warehouse_create'), 403);

        $this->warehouse = Warehouse::make();

        $this->createModal = true;
    }

    public function delete(Warehouse $warehouse)
    {
        abort_if(Gate::denies('warehouse_delete'), 403);

        $warehouse->delete();

        $this->alert('success', 'Warehouse successfully deleted.');
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('warehouse_delete'), 403);

        Warehouse::whereIn('id', $this->selected)->delete();

        $this->alert('success', 'Warehouses successfully deleted.');
    }

}
