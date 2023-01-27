<?php

declare(strict_types=1);

namespace App\Http\Livewire\Adjustment;

use App\Http\Livewire\WithSorting;
use App\Models\Adjustment;
use App\Traits\Datatable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

    /** @var string[] */
    public $listeners = [
        'showModal', 'editModal', 'createModal',
        'refreshIndex' => '$refresh', 'delete'
    ];

    public $showModal = false;

    public $createModal;

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
        'adjustment.date'      => ['date', 'required'],
        'adjustment.note'      => ['string', 'max:255', 'nullable'],
        'adjustment.reference' => ['string', 'max:255', 'nullable'],
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Adjustment())->orderable;
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('adjustment_access'), 403);

        $query = Adjustment::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $adjustments = $query->paginate($this->perPage);

        return view('livewire.adjustment.index', compact('adjustments'));
    }

    public function createModal(): void
    {
        // abort_if(Gate::denies('adjustment_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->adjustment = new Adjustment();

        $this->createModal = true;
    }

    public function create(): void
    {
        // abort_if(Gate::denies('adjustment_create'), 403);

        $this->validate();

        Adjustment::create($this->adjustment);

        $this->reset('createModal');

        $this->alert('success', __('Adjustment created successfully.'));
    }

    public function editModal(Adjustment $adjustment): void
    {
        // abort_if(Gate::denies('adjustment_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->adjustment = $adjustment;

        $this->editModal = true;
    }

    public function update(): void
    {
        // abort_if(Gate::denies('adjustment_edit'), 403);

        $this->validate();

        $this->adjustment->save();

        $this->editModal = false;

        $this->alert('success', __('Adjustment updated successfully.'));
    }

    public function showModal(Adjustment $adjustment): void
    {
        // abort_if(Gate::denies('adjustment_show'), 403);

        $this->adjustment = Adjustment::find($adjustment->id);

        $this->showModal = true;
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
