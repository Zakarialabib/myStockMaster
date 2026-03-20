<?php

declare(strict_types=1);

namespace App\Livewire\Sales\Payment;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Livewire\Utils\Datatable;
use App\Models\SalePayment;
use App\Models\Sale;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\WithAlert;

class Index extends Component
{
    use Datatable;
    use WithAlert;

    public $sale;

    public $model = SalePayment::class;

    public $showPayments = false;

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

    #[On('showPayments')]
    public function showPayments($id): void
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->sale = Sale::findOrFail($id);
        $this->sale_id = $this->sale->id;
        $this->showPayments = true;
    }

    public function deleteModal($id): void
    {
        $this->confirm(__('Are you sure you want to delete this payment?'), [
            'onConfirmed' => 'deletePayment',
            'params'      => ['id' => $id],
        ]);
    }

    #[On('deletePayment')]
    public function delete($id): void
    {
        abort_if(Gate::denies('sale payment_delete'), 403);

        $salepayment = SalePayment::findOrFail($id);

        $salepayment->delete();

        // Update sale status
        Sale::where('id', $salepayment->sale_id)->update([
            'status'         => SaleStatus::PENDING,
            'payment_status' => PaymentStatus::PENDING,
        ]);

        $this->alert('success', __('Sale Payment Deleted Successfully!'));
    }
}
