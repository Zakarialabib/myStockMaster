<?php

namespace App\Http\Livewire\Quotations;

use App\Http\Livewire\WithSorting;
use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $quotation;

    public $listeners = [
        'confirmDelete', 'delete', 'showModal',
    ];

    /** @var boolean */
    public $showModal = false;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public array $listsForFields = [];

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

    public function mount()
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Quotation)->orderable;
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('access_quotations'), 403);

        $query = Quotation::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $quotations = $query->paginate($this->perPage);

        return view('livewire.quotations.index', compact('quotations'));
    }

    public function showModal(Quotation $quotation)
    {
        abort_if(Gate::denies('access_quotations'), 403);

        $this->quotation = Customer::findOrFail($quotation->customer_id);

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('quotation_delete'), 403);

        Quotation::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Quotation $product)
    {
        abort_if(Gate::denies('quotation_delete'), 403);

        $product->delete();

        $this->alert('success', __('Quotation deleted successfully.'));
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['customers'] = Customer::pluck('name', 'id')->toArray();
    }
}
