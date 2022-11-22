<?php

namespace App\Http\Livewire\Adjustment;

use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\{Component, WithFileUploads, WithPagination};
use App\Models\Adjustment;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;

    public $adjustment;

    public $listeners = ['confirmDelete', 'delete', 'showModal', 'editModal', 'createModal'];

    public $showModal;

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

    public array $rules = [
        'adjustment.date' => ['date', 'required'],
        'adjustment.note' => ['string', 'max:255', 'nullable'],
        'adjustment.reference' => ['string', 'max:255', 'nullable'],
    ];

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Adjustment())->orderable;
    }

    public function render()
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

    public function createModal()
    {
        abort_if(Gate::denies('adjustment_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->adjustment = new Adjustment();

        $this->createModal = true;
    }

    public function create()
    {
        abort_if(Gate::denies('adjustment_create'), 403);

        $this->validate();

        Adjustment::create($this->adjustment);

        $this->reset('createModal');

        $this->alert('success', __('Adjustment created successfully.'));
    }

    public function editModal(Adjustment $adjustment)
    {
        abort_if(Gate::denies('adjustment_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->adjustment = $adjustment;

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('adjustment_edit'), 403);

        $this->validate();

        $this->adjustment->save();

        $this->editModal = false;

        $this->alert('success', __('Adjustment updated successfully.'));
    }

    public function showModal(Adjustment $adjustment)
    {
        abort_if(Gate::denies('adjustment_show'), 403);

        $this->adjustment = Adjustment::find($adjustment->id);

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('adjustment_delete'), 403);

        Adjustment::whereIn('id', $this->selected)->delete();

        $this->resetSelected();

        $this->alert('success', __('Adjustment deleted successfully.'));
    }

    public function delete(Adjustment $adjustment)
    {
        abort_if(Gate::denies('adjustment_delete'), 403);

        $adjustment->delete();

        $this->alert('success', __('Adjustment deleted successfully.'));
    }
}
