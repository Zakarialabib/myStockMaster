<?php

namespace App\Http\Livewire\Currency;

use Livewire\Component;
use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Currency;

class Index extends Component
{
    use WithPagination, WithSorting, WithConfirmation, WithFileUploads;

    public int $perPage;

    public $listeners = ['confirmDelete', 'delete', 'showModal', 'editModal'];

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

    public function showModal(Currency $currency)
    {
        abort_if(Gate::denies('currency_show'), 403);

        $this->emit('showModal', $currency);
    }

    public function editModal(Currency $currency)
    {
        abort_if(Gate::denies('currency_edit'), 403);

        $this->emit('editModal', $currency);
    }

    public function delete(Currency $currency)
    {
        abort_if(Gate::denies('currency_delete'), 403);

        $currency->delete();
    }
    
}
