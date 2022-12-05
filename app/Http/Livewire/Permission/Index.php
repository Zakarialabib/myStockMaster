<?php

namespace App\Http\Livewire\Permission;

use App\Http\Livewire\WithSorting;
use App\Models\Permission;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;

    public $permission;

    public $listeners = ['show', 'confirmDelete', 'delete', 'createModal', 'editModal'];

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

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Permission)->orderable;
    }

    public function render(): View|Factory
    {
        $query = Permission::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $permissions = $query->paginate($this->perPage);

        return view('livewire.permission.index', compact('permissions'));
    }

    public function createModal(): void
    {
        abort_if(Gate::denies('permission_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        Permission::create($this->permission);

        $this->createModal = false;

        $this->alert('success', __('Permission created successfully.'));
    }

    public function editModal(Permission $permission): void
    {
        abort_if(Gate::denies('permission_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->permission = Permission::find($permission->id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->permission->save();

        $this->editModal = false;

        $this->alert('success', __('Permission updated successfully.'));
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('permission_delete'), 403);

        Permission::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Permission $permission): void
    {
        abort_if(Gate::denies('permission_delete'), 403);

        $permission->delete();

        $this->alert('success', __('Permission deleted successfully.'));
    }
}
