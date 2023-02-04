<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Http\Livewire\WithSorting;
use App\Models\User;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $user;

    /** @var string[] */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal', 'editModal', 'delete',
    ];

    /** @var bool */
    public $showModal = false;

    /** @var bool */
    public $editModal = false;

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
        'user.name'     => 'required|string|max:255',
        'user.email'    => 'required|email|unique:users,email',
        'user.password' => 'required|string|min:8',
        'user.phone'    => 'required|numeric',
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new User())->orderable;
    }

    public function render(): View|Factory
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

        $this->alert('warning', __('User deleted successfully!'));
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

    public function update(): void
    {
        $this->validate();

        $this->user->save();

        $this->alert('success', __('User Updated Successfully'));

        $this->editModal = false;
    }
}
