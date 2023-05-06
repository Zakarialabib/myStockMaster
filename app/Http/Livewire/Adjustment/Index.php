<?php

declare(strict_types=1);

namespace App\Http\Livewire\Adjustment;

use App\Http\Livewire\WithSorting;
use App\Models\Adjustment;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $adjustment;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh', 'delete',
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
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Adjustment())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('adjustment_access'), 403);

        $query = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $adjustments = $query->paginate($this->perPage);

        return view('livewire.adjustment.index', compact('adjustments'));
    }

    public function deleteSelected(): void
    {
        // abort_if(Gate::denies('adjustment_delete'), 403);

        Adjustment::whereIn('id', $this->selected)->delete();

        $this->resetSelected();

        $this->alert('success', __('Adjustment deleted successfully.'));
    }

    public function delete(Adjustment $adjustment): void
    {
        abort_if(Gate::denies('adjustment_delete'), 403);

        $adjustment->delete();

        $this->alert('success', __('Adjustment deleted successfully.'));
    }
}
