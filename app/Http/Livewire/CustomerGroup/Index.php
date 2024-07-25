<?php

declare(strict_types=1);

namespace App\Http\Livewire\CustomerGroup;

use App\Http\Livewire\WithSorting;
use App\Models\CustomerGroup;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $customergroup;

    public $showModal = false;

    /** @var array<string> */
    public $listeners = [
        'showModal',
        'refreshIndex' => '$refresh',
        'delete',
    ];

    /** @var array<array<string>> */
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

    public function mount(): void
    {
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new CustomerGroup())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('customer_group_access'), 403);

        $query = CustomerGroup::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $customergroups = $query->paginate($this->perPage);

        return view('livewire.customer-group.index', compact('customergroups'));
    }

    public function showModal($id): void
    {
        abort_if(Gate::denies('customer_group_show'), 403);

        $this->customergroup = CustomerGroup::where('id', $id)->get();

        $this->showModal = true;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('customer_group_delete'), 403);

        CustomerGroup::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(CustomerGroup $customergroup): void
    {
        abort_if(Gate::denies('customer_group_delete'), 403);

        $customergroup->delete();

        $this->alert('success', __('Expense Category Deleted Successfully.'));
    }
}
