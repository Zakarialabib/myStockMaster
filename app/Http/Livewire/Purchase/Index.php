<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Purchase;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $purchase;

    public int $perPage;

    public $listeners = ['confirmDelete', 'delete', 'showModal', 'editModal', 'createModal'];

    public $showModal;

    public $createModal;

    public $editModal;

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

    public array $rules = [
        'supplier_id' => 'required|numeric',
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
        $this->selectPage = false;
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Purchase())->orderable;
    }

    public function render()
    {
        $query = Purchase::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $purchases = $query->paginate($this->perPage);

        return view('livewire.purchase.index', compact('purchases'));
    }

    public function createModal()
    {
        abort_if(Gate::denies('purchase_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset();

        $this->createModal = true;
    }

    public function create()
    {
        abort_if(Gate::denies('purchase_create'), 403);

        $this->validate();

        Purchase::create($this->purchase);

        $this->createModal = false;

        $this->alert('success', 'Purchase created successfully.');
    }

    public function editModal(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = $purchase;

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->purchase->save();

        $this->editModal = false;

        $this->alert('success', 'Purchase updated successfully.');
    }

    public function showModal(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset();

        $this->purchase = $purchase;

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        Purchase::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        $purchase->delete();
    }

}
