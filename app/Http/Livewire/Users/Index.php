<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\WithSorting;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert;

    public $listeners = ['confirmDelete', 'delete', 'export', 'import','refreshIndex','showModal','editModal'];

    public $showModal;

    public $editModal;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public $refreshIndex;

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

    public function refreshIndex()
    {
        $this->resetPage();
    }

    public array $rules = [
        'user.name' => 'required|string|max:255',
        'user.email' => 'required|email|unique:users,email',
        'user.password' => 'required|string|min:8',
        'user.phone' => 'required|numeric',
        'user.city' => 'nullable',
        'user.country' => 'nullable',
        'user.address' => 'nullable',
        'user.tax_number' => 'nullable',
    ];

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new User())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('user_access'), 403);

        $query = User::with(['roles'])->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $users = $query->paginate($this->perPage);

        return view('livewire.users.index', compact('users'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('user_delete'), 403);

        User::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(User $user)
    {
        abort_if(Gate::denies('user_delete'), 403);

        $user->delete();

        $this->alert('warning', __('User deleted successfully!') );

    }

    public function showModal(User $user)
    {
        $this->user = User::find($user->id);

        $this->showModal = true;
    }

    public function editModal(User $user)
    {
        abort_if(Gate::denies('user_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();
        
        $this->user = User::find($user->id);

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->user->save();

        $this->alert('success', __('User Updated Successfully'));

        $this->editModal = false;

    }
}
