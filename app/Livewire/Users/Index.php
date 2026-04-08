<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use Livewire\Attributes\Title;

use App\Livewire\Utils\Datatable;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

#[Title('Users List')]
class Index extends Component
{
    use Datatable;
    use WithAlert;

    public bool $showModal = false;

    public string $model = User::class;

    public mixed $user = null;

    public mixed $role = null;

    public mixed $warehouse_id = null;

    public mixed $filterRole = null;

    public function filterRole(mixed $role): void
    {
        $this->filterRole = $role;
        $this->resetPage(); // Reset pagination to the first page
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('user_access'), 403);

        $query = User::query()->when($this->warehouse_id, function ($query): void {
            $query->where('warehouse_id', $this->warehouse_id);
        })->with('roles')->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $users = $query->paginate($this->perPage);

        return view('livewire.users.index', ['users' => $users]);
    }

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    #[Computed]
    public function roles()
    {
        return Role::query()->pluck('name', 'id');
    }

    #[Computed]
    public function warehouses()
    {
        return Warehouse::query()->pluck('name', 'id');
    }

    // assign or change user role
    public function assignRole(User $user, mixed $role): void
    {
        $user->assignRole($role);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('user_delete'), 403);

        User::query()->whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(User $user): void
    {
        abort_if(Gate::denies('user_delete'), 403);

        $user->delete();

        $this->alert('warning', __('User deleted successfully!'));
    }

    public function showModal(User $user): void
    {
        $this->user = $user;

        $this->showModal = true;
    }
}
