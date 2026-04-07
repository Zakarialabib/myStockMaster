<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Livewire\Utils\Datatable;
use App\Models\Adjustment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]

class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    /** @var mixed */
    public mixed $adjustment;

    public string $model = Adjustment::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('adjustment_access'), 403);

        $query = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $adjustments = $query->paginate($this->perPage);

        return view('livewire.adjustment.index', ['adjustments' => $adjustments]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('adjustment_delete'), 403);

        Adjustment::query()->whereIn('id', $this->selected)->delete();

        $this->resetSelected();

        $this->alert('success', __('Adjustment deleted successfully.'));
    }

    public function deleteModal(int|string $adjustment): void
    {
        $confirmationMessage = __('Are you sure you want to delete this adjustment?');

        $this->confirm($confirmationMessage, [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'delete',
        ]);

        $this->adjustment = $adjustment;
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies('adjustment_delete'), 403);

        $adjustment = Adjustment::query()->findOrFail($this->adjustment);
        $adjustment->delete();

        $this->alert('success', __('Adjustment deleted successfully!'));
    }

    #[On('refreshIndex')]
    public function refreshIndex(): void
    {
        $this->resetPage();
    }
}
