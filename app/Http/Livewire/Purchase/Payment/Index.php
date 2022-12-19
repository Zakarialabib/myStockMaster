<?php

declare(strict_types=1);

namespace App\Http\Livewire\Purchase\Payment;

use App\Http\Livewire\WithSorting;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;

    public $purchase;

    /** @var string[] */
    public $listeners = [
        'showPayments',
        'refreshIndex' => '$refresh',
    ];

    public $refreshIndex;

    public $showPayments;

    public int $perPage;
    /** @var array */
    public array $orderable;

    /** @var string */
    public string $search = '';

    /** @var array */
    public array $selected = [];

    /** @var array */
    public array $paginationOptions;

    public array $listsForFields = [];

    public $purchase_id;

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

    public function mount($purchase): void
    {
        $this->purchase = $purchase;

        if ($purchase) {
            $this->purchase_id = $purchase->id;
        }

        $this->perPage = 10;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new PurchasePayment())->orderable;
    }

    public function render(): View|Factory
    {
        //    abort_if(Gate::denies('access_purchase_payments'), 403);

        $query = PurchasePayment::where('purchase_id', $this->purchase_id)->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $purchasepayments = $query->paginate($this->perPage);

        return view('livewire.purchase.payment.index', compact('purchasepayments'));
    }

    public function showPayments($purchase_id): void
    {
        abort_if(Gate::denies('access_purchases'), 403);

        $this->purchase_id = $purchase_id;

        $this->showPayments = true;
    }
}
