<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Livewire\Utils\Datatable;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Lazy]
class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public $purchase;

    public $model = Purchase::class;

    public string $startDate;

    public string $endDate;

    public function filterByType($type): void
    {
        switch ($type) {
            case 'day':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
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

    public function render()
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

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        Purchase::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function deleteModal($id): void
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

        Purchase::findOrFail($this->purchase)->delete();

        $this->alert('success', __('Purchase deleted successfully.'));
    }
}
