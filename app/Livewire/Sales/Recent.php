<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Livewire\Utils\Datatable;
use App\Models\Sale;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class Recent extends Component
{
    use WithAlert;
    use WithFileUploads;
    use Datatable;

    public $sale;

    public $showModal = false;

    public $recentSalesVisible = false;

    public $model = Sale::class;

    public function render()
    {
        abort_if(Gate::denies('sale_access'), 403);

        $query = Sale::with('customer', 'saleDetails')->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $sales = $query->paginate($this->perPage);

        return view('livewire.sales.recent', ['sales' => $sales]);
    }

    #[On('showModal')]
    public function showModal($id): void
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->sale = Sale::with('saleDetails')->findOrFail($id);

        $this->showModal = true;
    }

    #[On('recentSales')]
    public function recentSales(): void
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->recentSalesVisible = true;
    }
}
