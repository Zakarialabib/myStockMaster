<?php

declare(strict_types=1);

namespace App\Livewire\Purchase\Payment;

use App\Livewire\Utils\Datatable;
use App\Models\PurchasePayment;
use App\Models\Purchase;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use LivewireAlert;
    use Datatable;

    public $purchase;

    public $model = PurchasePayment::class;

    public $showPayments;

    public $listsForFields = [];

    public $purchase_id;

    public function mount($purchase): void
    {
        $this->purchase = $purchase;
    }

    public function render()
    {
        abort_if(Gate::denies('purchase payment_access'), 403);

        $query = PurchasePayment::where('purchase_id', $this->purchase->id)->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $purchasepayments = $query->paginate($this->perPage);

        return view('livewire.purchase.payment.index', ['purchasepayments' => $purchasepayments]);
    }

    #[On('showPayments')]
    public function showPayments($purchase_id): void
    {
        abort_if(Gate::denies('purchase payment_access'), 403);

        $this->purchase = Purchase::findOrFail($purchase_id);

        $this->showPayments = true;
    }
}
