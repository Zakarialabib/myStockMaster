<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Livewire\Utils\Datatable;
use App\Livewire\Utils\HasDelete;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Warehouses')]
class Index extends Component
{
    use Datatable;
    use HasDelete;
    use WithAlert;
    use WithFileUploads;

    public mixed $warehouse;

    public bool $showModal = false;

    public string $model = Warehouse::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('warehouse_access'), 403);

        $query = Warehouse::with('products')->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $warehouses = $query->paginate($this->perPage);

        return view('livewire.warehouses.index', ['warehouses' => $warehouses]);
    }

    #[On('showModal')]
    public function showModal(Warehouse $warehouse): void
    {
        abort_if(Gate::denies('warehouse_show'), 403);

        $this->warehouse = Warehouse::query()->find($warehouse->id);

        $this->showModal = true;
    }
}
