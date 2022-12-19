<?php

declare(strict_types=1);

namespace App\Http\Livewire\Purchase;

use App\Http\Livewire\WithSorting;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Enums\PaymentStatus;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;

    public $purchase;

    public int $perPage;

    public $selectPage;

    /** @var string[] */
    public $listeners = [
        'showModal', 'paymentModal',
        'refreshIndex' => '$refresh',
    ];

    public $showModal = false;

    public $paymentModal = false;

    public $purchase_id;
    /** @var array */
    public array $orderable;

    /** @var string */
    public string $search = '';

    /** @var array */
    public array $selected = [];

    /** @var array */
    public array $paginationOptions;

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

    public array $rules = [
        'supplier_id'         => 'required|numeric',
        'reference'           => 'required|string|max:255',
        'tax_percentage'      => 'required|integer|min:0|max:100',
        'discount_percentage' => 'required|integer|min:0|max:100',
        'shipping_amount'     => 'required|numeric',
        'total_amount'        => 'required|numeric',
        'paid_amount'         => 'required|numeric',
        'status'              => 'required|string|max:255',
        'payment_method'      => 'required|string|max:255',
        'note'                => 'nullable|string|max:1000',
    ];

    public function mount(): void
    {
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Purchase())->orderable;
    }

    public function render(): View|Factory
    {
        $query = Purchase::with(['supplier', 'purchaseDetails', 'purchaseDetails.product'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $purchases = $query->paginate($this->perPage);

        return view('livewire.purchase.index', compact('purchases'));
    }

    public function showModal(Purchase $purchase): void
    {
        abort_if(Gate::denies('purchase_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = Purchase::find($purchase->id);

        $this->showModal = true;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        Purchase::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Purchase $purchase): void
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        $purchase->delete();
    }

    //  Payment modal

    public function paymentModal(Purchase $purchase): void
    {
        abort_if(Gate::denies('purchase_payment'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = $purchase;
        $this->date = Carbon::now()->format('Y-m-d');
        $this->reference = 'ref-'.Carbon::now()->format('YmdHis');
        $this->amount = $purchase->due_amount;
        $this->payment_method = 'Cash';
        $this->purchase_id = $purchase->id;
        $this->paymentModal = true;
    }

    public function paymentSave(): void
    {
        DB::transaction(function () {
            $this->validate(
                [
                    'date'           => 'required|date',
                    'reference'      => 'required|string|max:255',
                    'amount'         => 'required|numeric',
                    'payment_method' => 'required|string|max:255',
                ]
            );

            $purchase = Purchase::find($this->purchase_id);

            PurchasePayment::create([
                'date'           => $this->date,
                'reference'      => $this->reference,
                'amount'         => $this->amount,
                'note'           => $this->note ?? null,
                'purchase_id'    => $this->purchase_id,
                'payment_method' => $this->payment_method,
            ]);

            $purchase = Purchase::findOrFail($this->purchase_id);

            $due_amount = $purchase->due_amount - $this->amount;

            if ($due_amount == $purchase->total_amount) {
                $payment_status = PaymentStatus::Due;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::Partial;
            } else {
                $payment_status = PaymentStatus::Paid;
            }

            $purchase->update([
                'paid_amount'    => ($purchase->paid_amount + $this->amount) * 100,
                'due_amount'     => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);

            $this->emit('refreshIndex');

            $this->alert('success', __('Payment created successfully.'));

            $this->paymentModal = false;
        });
    }
}
