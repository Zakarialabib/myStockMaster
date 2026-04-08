<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use Livewire\Attributes\Title;

use App\Exports\PurchaseExport;
use App\Livewire\Utils\Datatable;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]

#[Title('Purchases')]
class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public mixed $purchase;

    public string $model = Purchase::class;

    public string $startDate;

    public string $endDate;

    public function filterByType(mixed $type): void
    {
        switch ($type) {
            case 'day':
                $this->startDate = today()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');

                break;
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');

                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');

                break;
        }
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->select('id', 'name')->get();
    }

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $query = Purchase::with(['supplier', 'user', 'purchaseDetails', 'purchasePayments', 'purchaseDetails.product'])
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $purchases = $query->paginate($this->perPage);

        return view('livewire.purchase.index', ['purchases' => $purchases]);
    }

    public function updateStatus(int $id, string $status): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $purchase = Purchase::query()->findOrFail($id);
        $purchase->update(['status' => $status]);

        $this->alert('success', __('Purchase status updated successfully.'));
    }

    public function updatePaymentStatus(int $id, string $payment_status): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $purchase = Purchase::query()->findOrFail($id);
        $purchase->update(['payment_status' => $payment_status]);

        $this->alert('success', __('Purchase payment status updated successfully.'));
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        Purchase::query()->whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function deleteModal(int|string $id): void
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'delete',
        ]);
        $this->purchase = $id;
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        Purchase::query()->findOrFail($this->purchase)->delete();

        $this->alert('success', __('Purchase deleted successfully.'));
    }

    public function downloadSelected()
    {
        abort_if(Gate::denies('purchase_export'), 403);

        $purchases = Purchase::query()->whereIn('id', $this->selected)->get();

        return (new PurchaseExport($purchases))->download('purchases.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('purchase_export'), 403);

        return $this->callExport()->download('purchases.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('purchase_export'), 403);

        return $this->callExport()->forModels($this->selected)->download('purchases.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('purchase_export'), 403);

        return $this->callExport()->download('purchases.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): PurchaseExport
    {
        return new PurchaseExport;
    }
}
