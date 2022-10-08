<?php

namespace App\Http\Livewire\Currency;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use App\Support\HasAdvancedFilter;
use App\Models\Currency;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert, HasAdvancedFilter;

    public $currency;

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

    public function resetSelected()
    {
        $this->selected = [];
    }

    public array $rules = [
        'currency.currency_name' => 'required|string|max:255',
        'currency.code' => 'required|string|max:255',
        'currency.symbol' => 'required|string|max:255',
        'currency.exchange_rate' => 'required|numeric',
    ];

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Currency())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('currency_access'), 403);
        
        $query = Currency::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);
        $currencies = $query->paginate($this->perPage);

        return view('livewire.currency.index', compact('currencies'));
    }

    public function createModal()
    {
        abort_if(Gate::denies('currency_create'), 403);

        $this->createModal = true;
    }

    public function create()
    {
        abort_if(Gate::denies('currency_create'), 403);
        
        $this->currency = new Currency();

        $this->showModal = true;

        $this->alert('success', 'Currency created successfully!');
    }

    public function showModal(Currency $currency)
    {
        abort_if(Gate::denies('currency_show'), 403);

        $this->currency = $currency;

        $this->showModal = true;
    }

    public function editModal(Currency $currency)
    {
        abort_if(Gate::denies('currency_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->currency = $currency;

        $this->editModal = true;

    }

    public function update(Currency $currency)
    {
        abort_if(Gate::denies('currency_edit'), 403);

        $this->validate();

        $currency->update($this->currency);

        $this->showModal = false;

        $this->alert('success', 'Currency updated successfully!');
    }

    public function delete(Currency $currency)
    {
        abort_if(Gate::denies('currency_delete'), 403);

        $currency->delete();

        $this->alert('success', 'Currency deleted successfully!');
    }
    
}
