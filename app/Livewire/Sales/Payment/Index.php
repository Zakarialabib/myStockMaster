<?php

declare(strict_types=1);

namespace App\Livewire\Sales\Payment;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Livewire\Utils\Datatable;
use App\Models\SalePayment;
use App\Models\Sale;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{
    use LivewireAlert;
    use Datatable;

    public $sale;

    public $model = SalePayment::class;

    public $listeners = [
        'showPayments',
    ];

    public $showPayments;

    public $sale_id;

    public function render()
    {
        abort_if(Gate::denies('sale payment_access'), 403);

        $query = SalePayment::when($this->sale_id, function ($query): void {
            $query->where('sale_id', $this->sale_id);
        })
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $salepayments = $query->paginate($this->perPage);

        return view('livewire.sales.payment.index', ['salepayments' => $salepayments]);
    }

    public function showPayments($id): void
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->sale = Sale::findOrFail($id);

        $this->showPayments = true;
    }

    public function delete($id): void
    {
        abort_if(Gate::denies('sale payment_delete'), 403);

        $salepayment = SalePayment::findOrFail($id);

        $salepayment->delete();

        // need to change status of sale , if all payment deleted

        Sale::where('id', $salepayment->sale_id)->update([
            'status'         => SaleStatus::PENDING,
            'payment_status' => PaymentStatus::PENDING,
        ]);

        $this->dispatch('refreshIndex');

        $this->alert('success', __('Sale Payment Deleted Successfully!'));
    }
}
