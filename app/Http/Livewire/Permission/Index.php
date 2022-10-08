<?php

namespace App\Http\Livewire\Permission;

use Livewire\Component;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use App\Http\Livewire\WithSorting;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Support\HasAdvancedFilter;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert , HasAdvancedFilter;

    public $permission;

    public $listeners = ['show','confirmDelete', 'delete', 'createModal', 'editModal'];

    public $show;
    
    public $createModal;

    public $editModal;

    public int $perPage;

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

    public function resetSelected()
    {
        $this->selected = [];
    }

    protected function rules(): array
    {
        return [
            'permission.name' => [
                'string',
                'required',
            ],
            'permission.label' => [
                'string',
                'required',
            ],
            'permission.description' => [
                'string',
                'required',
            ],
        ];
    }

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Permission())->orderable;
    }

    public function render()
    {
        $query = Permission::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $permissions = $query->paginate($this->perPage);

        return view('livewire.permission.index', compact('permissions'));
    }

    public function createModal()
    {
        abort_if(Gate::denies('permission_create'), 403);
        
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create()
    {
        $this->validate();

        Permission::create($this->permission);

        $this->createModal = false;

        $this->alert('success', 'Permission created successfully.');
    }

    public function editModal(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->permission = $permission;

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->permission->save();

        $this->editModal = false;

        $this->alert('success', 'Permission updated successfully.');
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('permission_delete'), 403);

        Permission::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), 403);

        $permission->delete();

        $this->alert('success', 'Permission deleted successfully.');
    }
}
