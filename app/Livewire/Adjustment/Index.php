<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Livewire\Utils\Datatable;
use App\Models\Adjustment;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Traits\WithAlert;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
#[Lazy]
class Index extends Component
{
    use WithAlert;
    use Datatable;
    use WithFileUploads;

    public function placeholder()
    {
        return view('livewire.placeholders.skeleton');
    }
    
    /** @var mixed */
    public $adjustment;

    public $model = Adjustment::class;

    public function render()
    {
        abort_if(Gate::denies('adjustment_access'), 403);

        $query = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $adjustments = $query->paginate($this->perPage);

        return view('livewire.adjustment.index', ['adjustments' => $adjustments]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('adjustment_delete'), 403);

        Adjustment::whereIn('id', $this->selected)->delete();

        $this->resetSelected();

        $this->alert('success', __('Adjustment deleted successfully.'));
    }

    public function deleteModal($adjustment): void
    {
        $confirmationMessage = __('Are you sure you want to delete this adjustment?');

        $this->confirm($confirmationMessage, [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);

        $this->adjustment = $adjustment;
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies('adjustment_delete'), 403);

        $adjustment = Adjustment::findOrFail($this->adjustment);
        $adjustment->delete();

        $this->alert('success', __('Adjustment deleted successfully!'));
    }

    #[On('refreshIndex')]
    public function refreshIndex(): void
    {
        $this->resetPage();
    }
}
