<?php

declare(strict_types=1);

namespace App\Livewire\SaleReturn;

use App\Enums\PaymentStatus;
use App\Livewire\Utils\Datatable;
use App\Models\SaleReturn;
use App\Models\SaleReturnPayment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

#[Layout('layouts.app')]
#[Title('Sale Returns')]
class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public mixed $salereturn;

    public string $model = SaleReturn::class;

    public bool $showModal = false;

    public bool $importModal = false;

    public bool $paymentModal = false;

    public mixed $salereturn_id;

    public mixed $reference;

    public mixed $total_amount;

    public mixed $due_amount;

    public mixed $paid_amount;

    #[Validate('required|date')]
    public ?string $date = null;

    #[Validate('required|numeric')]
    public mixed $amount;

    #[Validate('required|string|max:255')]
    public mixed $payment_method;

    #[Validate('nullable|string|max:1000')]
    public mixed $note;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('sale_access'), 403);

        $query = SaleReturn::with(['customer', 'saleReturnPayments', 'saleReturnDetails'])
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $salereturns = $query->paginate($this->perPage);

        return view('livewire.sale-return.index', ['salereturns' => $salereturns]);
    }

    public function showModal(SaleReturn $salereturn): void
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->salereturn = SaleReturn::query()->find($salereturn->id);

        $this->showModal = true;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('sale_delete'), 403);

        SaleReturn::query()->whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(SaleReturn $saleReturn): void
    {
        abort_if(Gate::denies('sale_delete'), 403);

        $saleReturn->delete();

        $this->dispatch('refreshIndex');

        $this->alert('success', __('SaleReturn deleted successfully.'));
    }

    public function paymentModal(SaleReturn $salereturn): void
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->salereturn = $salereturn;
        $this->date = date('Y-m-d');
        $this->amount = $this->salereturn->due_amount;
        $this->payment_method = 'Cash';
        $this->salereturn_id = $salereturn->id;
        $this->paymentModal = true;
    }

    public function paymentSave(): void
    {
        try {
            $this->validate();

            SaleReturnPayment::query()->create([
                'date' => $this->date,
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'sale_id' => $this->salereturn_id,
                'payment_method' => $this->payment_method,
                // 'user_id'        => Auth::user()->id,
            ]);

            $salereturn = SaleReturn::query()->findOrFail($this->salereturn_id);

            $due_amount = $salereturn->due_amount - $this->amount;

            if ($due_amount === $salereturn->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $salereturn->update([
                'paid_amount' => ($salereturn->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);

            $this->alert('success', __('Sale Return Payment created successfully.'));

            $this->dispatch('refreshIndex');

            $this->paymentModal = false;
        } catch (Throwable $throwable) {
            $this->alert('error', __('Error.') . $throwable->getMessage());
        }
    }
}
