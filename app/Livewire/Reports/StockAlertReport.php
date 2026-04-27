<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StockAlertReport extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(history: true)]
    public ?string $warehouse_id = null;

    #[Url(history: true)]
    public int $perPage = 10;

    public function mount(): void
    {
        $this->warehouse_id = $this->warehouse_id ?? '';
    }

    #[Computed]
    public function warehouses()
    {
        return Warehouse::query()->select(['id', 'name'])->get();
    }

    #[Computed]
    public function stockAlert()
    {
        return ProductWarehouse::with(['product', 'warehouse'])
            ->when($this->warehouse_id, fn ($q) => $q->where('warehouse_id', $this->warehouse_id))
            ->whereColumn('qty', '<=', 'stock_alert')
            ->paginate($this->perPage);
    }

    public function setThreshold(int $id, int $stockAlert): void
    {
        $productWarehouse = ProductWarehouse::findOrFail($id);
        $productWarehouse->update(['stock_alert' => $stockAlert]);

        $this->alert('success', __('Stock Alert Updated Successfully!'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.stock-alert-report');
    }
}
