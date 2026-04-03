<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Livewire\Utils\Datatable;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Lazy]
class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    /** @var mixed */
    public $warehouse;

    /** @var bool */
    public $showModal = false;

    public $model = Warehouse::class;

    public function render()
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

        $this->warehouse = Warehouse::find($warehouse->id);

        $this->showModal = true;
    }

    public function deleteSelectedModal(): void
    {
        $confirmationMessage = __('Are you sure you want to delete the selected warehouses? items can be recovered.');

        $this->confirm($confirmationMessage, [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'deleteSelected',
        ]);
    }

    #[On('deleteSelected')]
    public function deleteSelected(): void
    {
        abort_if(Gate::denies('warehouse_delete'), 403);

        Warehouse::whereIn('id', $this->selected)->delete();

        $deletedCount = count($this->selected);

        if ($deletedCount > 0) {
            $this->alert(
                'success',
                __(':count selected products and related warehouses deleted successfully! These items can be recovered.', ['count' => $deletedCount])
            );
        }

        $this->resetSelected();
    }

    public function delete(Warehouse $warehouse): void
    {
        abort_if(Gate::denies('warehouse_delete'), 403);

        $warehouse->delete();

        $this->alert('success', __('Warehouse deleted successfully!'));
    }
}
