<?php

declare(strict_types=1);

namespace App\Livewire\Role;

use App\Livewire\Utils\Datatable;
use App\Models\Role;
use App\Traits\WithAlert;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Index extends Component
{
    use Datatable;
    use WithAlert;

    public string $model = Role::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $query = Role::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.role.index', ['roles' => $lengthAwarePaginator]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Role::query()->whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Role $role): void
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();
    }
}
