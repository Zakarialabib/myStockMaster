<?php

namespace App\Http\Livewire\Currency;

use App\Http\Livewire\WithSorting;
use App\Models\Currency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;

    public $currency;

    public int $perPage;

    public $listeners = [
        'confirmDelete', 'delete', 'showModal',
        'editModal', 'refreshIndex',
    ];

    public $showModal;

    public $refreshIndex;

    public $editModal;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public $selectPage;

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

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }

    public function refreshIndex(): void
    {
        $this->resetPage();
    }

    public array $rules = [
        'currency.name' => 'required|string|max:255',
        'currency.code' => 'required|string|max:255',
        'currency.symbol' => 'required|string|max:255',
        'currency.exchange_rate' => 'required|numeric',
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Currency)->orderable;
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('currency_access'), 403);

        $query = Currency::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $currencies = $query->paginate($this->perPage);

        return view('livewire.currency.index', compact('currencies'));
    }

    public function showModal(Currency $currency): void
    {
        abort_if(Gate::denies('currency_show'), 403);

        $this->currency = Currency::find($currency->id);

        $this->showModal = true;
    }

    public function editModal(Currency $currency): void
    {
        abort_if(Gate::denies('currency_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->currency = Currency::find($currency->id);

        $this->editModal = true;
    }

    public function update(Currency $currency): void
    {
        abort_if(Gate::denies('currency_edit'), 403);

        $this->validate();

        $this->currency->save();

        $this->editModal = false;

        $this->alert('success', __('Currency updated successfully!'));
    }

    public function delete(Currency $currency): void
    {
        abort_if(Gate::denies('currency_delete'), 403);

        $currency->delete();

        $this->alert('success', __('Currency deleted successfully!'));
    }
}
