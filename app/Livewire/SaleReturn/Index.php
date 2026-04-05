<?php

declare(strict_types=1);

namespace App\Livewire\SaleReturn;

use App\Enums\PaymentStatus;
use App\Exports\SaleReturnExport;
use App\Livewire\Utils\Datatable;
use App\Models\SaleReturn;
use App\Models\SaleReturnPayment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public $salereturn;

    public $model = SaleReturn::class;

    public $showModal = false;

    public $importModal = false;

    public $paymentModal = false;

    public $salereturn_id;

    public $reference;

    public $total_amount;

    public $due_amount;

    public $paid_amount;

    #[Validate('required|date')]
    public $date;

    #[Validate('required|numeric')]
    public $amount;

    #[Validate('required|string|max:255')]
    public $payment_method;

    #[Validate('nullable|string|max:1000')]
    public $note = null;

    public function render()
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

        $this->salereturn = SaleReturn::find($salereturn->id);

        $this->showModal = true;
    }

    public function updateStatus(SaleReturn $saleReturn, $status): void
    {
        abort_if(Gate::denies('sale_return_update'), 403);

        $saleReturn->update(['status' => $status]);

        $this->alert('success', __('Status updated successfully.'));
    }

    public function updatePaymentStatus(SaleReturn $saleReturn, $payment_status): void
    {
        abort_if(Gate::denies('sale_return_update'), 403);

        $saleReturn->update(['payment_status' => $payment_status]);

        $this->alert('success', __('Payment status updated successfully.'));
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('sale_delete'), 403);

        SaleReturn::whereIn('id', $this->selected)->delete();

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

            SaleReturnPayment::create([
                'date' => $this->date,
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'sale_id' => $this->salereturn_id,
                'payment_method' => $this->payment_method,
                // 'user_id'        => Auth::user()->id,
            ]);

            $salereturn = SaleReturn::findOrFail($this->salereturn_id);

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
            $this->alert('error', __('Error ') . $throwable->getMessage());
        }
    }

    public function downloadSelected()
    {
        abort_if(Gate::denies('sale_return_access'), 403);

        $salereturns = SaleReturn::whereIn('id', $this->selected)->get();

        return (new SaleReturnExport($salereturns))->download('sale_returns.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('sale_return_access'), 403);

        return $this->callExport()->download('sale_returns.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('sale_return_access'), 403);

        return $this->callExport()->forModels($this->selected)->download('sale_returns.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('sale_return_access'), 403);

        return $this->callExport()->download('sale_returns.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): SaleReturnExport
    {
        return new SaleReturnExport;
    }
}
