<?php

declare(strict_types=1);

namespace App\Livewire\Warehouses;

use App\Livewire\Utils\Datatable;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Index extends Component
{
    use Datatable;
    use WithFileUploads;
    use LivewireAlert;

    /** @var mixed */
    public $warehouse;

    /** @var array<string> */
    public $listeners = [
        'delete',
    ];

    /** @var bool */
    public $showModal = false;

    public $model = Warehouse::class;

    public function render()
    {
        abort_if(Gate::denies('warehouse_access'), 403);

        $query = Warehouse::with('products')->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
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

    public function deleteModal(int $warehouse): void
    {
        $confirmationMessage = __('Are you sure you want to delete this warehouse? if something happens you can be recover it.');

        $this->confirm($confirmationMessage, [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);

        $this->warehouse = $warehouse;
    }

    public function deleteSelectedModal(): void
    {
        $confirmationMessage = __('Are you sure you want to delete the selected warehouses? items can be recovered.');

        $this->confirm($confirmationMessage, [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'deleteSelected',
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

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies('warehouse_delete'), 403);

        $warehouse = Warehouse::findOrFail($this->warehouse);
        $warehouse->delete();

        $this->alert('success', __('Warehouse deleted successfully!'));
    }
}
