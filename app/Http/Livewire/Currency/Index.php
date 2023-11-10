<?php

declare(strict_types=1);

namespace App\Http\Livewire\Currency;

use App\Http\Livewire\WithSorting;
use App\Models\Currency;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $currency;

    /** @var array<string> */
    public $listeners = [
        'showModal',
        'refreshIndex' => '$refresh',
        'delete',
    ];

    public $deleteModal = false;
    
    public $showModal = false;

    public $editModal = false;

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

    public function showModal(Currency $currency): void
    {
        abort_if(Gate::denies('currency_show'), 403);

        $this->currency = Currency::find($currency->id);

        $this->showModal = true;
    }
    public function deleteModal($currency=null)
    {
        $confirm = null;
        if ($currency === null) {
            $confirm = 'deleteSelected';    
        } else {
            $confirm = in_array($currency, $this->selected) ? 'deleteSelected' : 'delete'; 
        }

        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       =>  $confirm,
        ]);
        $this->currency = $currency;
    }

    public function deleteSelected()
    { 
        abort_if(Gate::denies('currency_delete'), 403);

        Currency::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete():void
    {
        abort_if(Gate::denies('currency_delete'), 403);

        $currency = Currency::findOrFail($this->currency);

        $currency->delete();

        $this->alert('success', __('Currency deleted successfully!'));
    }
}
