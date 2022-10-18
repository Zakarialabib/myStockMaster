<?php

namespace App\Http\Livewire\Quotations;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use App\Models\Quotation;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $quotation;

    public $listeners = ['confirmDelete', 'delete', 'showModal', 'editModal', 'createModal'];

    public $showModal;

    public $createModal;

    public $editModal;

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

    public array $rules = [
        'customer_id' => 'required|numeric',
        'reference' => 'required|string|max:255',
        'tax_percentage' => 'required|integer|min:0|max:100',
        'discount_percentage' => 'required|integer|min:0|max:100',
        'shipping_amount' => 'required|numeric',
        'total_amount' => 'required|numeric',
        'paid_amount' => 'required|numeric',
        'status' => 'required|string|max:255',
        'payment_method' => 'required|string|max:255',
        'note' => 'nullable|string|max:1000'
    ];

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Quotation())->orderable;
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('access_quotations'), 403);

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
        abort_if(Gate::denies('access_quotations'), 403);

        $this->quotation = $quotation;

        $this->showModal = true;
    }

    public function createModal()
    {
        abort_if(Gate::denies('create_quotations'), 403);

        $this->resetSelected();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create()
    {
        abort_if(Gate::denies('create_quotations'), 403);

        $this->validate();

        Quotation::create($this->quotation);

        $this->createModal = false;

        $this->alert('success', 'Quotation created successfully.');
    }

    public function editModal(Quotation $quotation)
    {
        abort_if(Gate::denies('edit_quotations'), 403);

        $this->resetSelected();

        $this->resetValidation();

        $this->quotation = $quotation;

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->quotation->save();

        $this->editModal   = false;

        $this->alert('success', 'Quotation updated successfully.');
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('delete_quotations'), 403);

        Quotation::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Quotation $product)
    {
        abort_if(Gate::denies('delete_quotations'), 403);

        $product->delete();

        $this->alert('success', 'Quotation deleted successfully.');
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['customers'] = Customer::pluck('name', 'id')->toArray();
    }

  
}
