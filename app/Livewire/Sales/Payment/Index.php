<?php

declare(strict_types=1);

namespace App\Livewire\Sales\Payment;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Livewire\Utils\Datatable;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use Datatable;
    use WithAlert;

    public mixed $sale;

    public string $model = SalePayment::class;

    public bool $showPayments = false;

    public mixed $sale_id;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('sale payment_access'), 403);

        $query = SalePayment::query()->when($this->sale_id, function ($query): void {
            $query->where('sale_id', $this->sale_id);
        })
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $salepayments = $query->paginate($this->perPage);

        return view('livewire.sales.payment.index', ['salepayments' => $salepayments]);
    }

    #[On('showPayments')]
    public function showPayments(mixed $id): void
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->sale = Sale::query()->findOrFail($id);
        $this->sale_id = $this->sale->id;
        $this->showPayments = true;
    }

    public function deleteModal(int|string $id): void
    {
        $this->confirm(__('Are you sure you want to delete this payment?'), [
            'onConfirmed' => 'deletePayment',
            'params' => ['id' => $id],
        ]);
    }

    #[On('deletePayment')]
    public function delete(int|string $id): void
    {
        abort_if(Gate::denies('sale payment_delete'), 403);

        $salepayment = SalePayment::query()->findOrFail($id);

        $salepayment->delete();

        // Update sale status
        Sale::query()->where('id', $salepayment->sale_id)->update([
            'status' => SaleStatus::PENDING,
            'payment_status' => PaymentStatus::PENDING,
        ]);

        $this->alert('success', __('Sale Payment Deleted Successfully!'));
    }
}
