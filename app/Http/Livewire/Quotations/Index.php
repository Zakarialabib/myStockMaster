<?php

declare(strict_types=1);

namespace App\Http\Livewire\Quotations;

use App\Http\Livewire\WithSorting;
use App\Models\Customer;
use App\Models\Quotation;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;
    use Datatable;

    public $quotation;

    /** @var string[] */
    public $listeners = [
        'showModal',
    ];

    /** @var bool */
    public $showModal = false;

    public array $listsForFields = [];

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

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Quotation())->orderable;
        $this->initListsForFields();
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $query = Quotation::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $quotations = $query->paginate($this->perPage);

        return view('livewire.quotations.index', compact('quotations'));
    }

    public function showModal(Quotation $quotation)
    {
        abort_if(Gate::denies('quotation_access'), 403);

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
