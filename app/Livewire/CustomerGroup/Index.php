<?php

declare(strict_types=1);

namespace App\Livewire\CustomerGroup;

use App\Livewire\Utils\Datatable;
use App\Models\CustomerGroup;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Customer Group')]
class Index extends Component
{
    use Datatable;
    use WithAlert;

    public mixed $customergroup;

    public mixed $customer_group_id;

    public ?int $warehouse_id = null;

    public bool $showModal = false;

    public string $model = CustomerGroup::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('customer-group_access'), 403);

        $query = CustomerGroup::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.customer-group.index', ['customergroups' => $lengthAwarePaginator]);
    }

    public function openShowModal(mixed $id): void
    {
        abort_if(Gate::denies('customer-group_show'), 403);

        $this->customergroup = CustomerGroup::query()->where('id', $id)->get();

        $this->showModal = true;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('customer-group_delete'), 403);

        CustomerGroup::query()->whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(CustomerGroup $customergroup): void
    {
        abort_if(Gate::denies('customer-group_delete'), 403);

        $customergroup->delete();

        $this->alert('success', __('Expense Category Deleted Successfully.'));
    }
}
