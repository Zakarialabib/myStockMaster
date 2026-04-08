<?php

declare(strict_types=1);

namespace App\Livewire\Purchase\Payment;

use App\Livewire\Utils\Datatable;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use Datatable;

    public mixed $purchase;

    public string $model = PurchasePayment::class;

    public mixed $showPayments;

    public $listsForFields = [];

    public mixed $purchase_id;

    public function mount(mixed $purchase = null): void
    {
        $this->purchase = $purchase;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('purchase payment_access'), 403);

        $query = PurchasePayment::query()->when($this->purchase, function ($query) {
            $query->where('purchase_id', $this->purchase->id);
        })->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $purchasepayments = $query->paginate($this->perPage);

        return view('livewire.purchase.payment.index', ['purchasepayments' => $purchasepayments]);
    }

    #[On('showPayments')]
    public function showPayments(mixed $purchase_id): void
    {
        abort_if(Gate::denies('purchase payment_access'), 403);

        $this->purchase = Purchase::query()->findOrFail($purchase_id);

        $this->showPayments = true;
    }
}
