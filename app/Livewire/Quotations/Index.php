<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Utils\Datatable;
use App\Livewire\Utils\HasDelete;
use App\Livewire\Utils\WithModels;
use App\Models\Quotation;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Quotations')]
class Index extends Component
{
    use Datatable;
    use HasDelete;
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    public $quotation;

    public $model = Quotation::class;

    public function render()
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $query = Quotation::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $quotations = $query->paginate($this->perPage);

        return view('livewire.quotations.index', ['quotations' => $quotations]);
    }

    #[On('delete')]
    public function delete(\App\Services\QuotationService $quotationService): void
    {
        abort_if(Gate::denies($this->getGateDelete()), 403);

        try {
            $quotation = Quotation::findOrFail($this->value);
            $quotationService->delete($quotation);
            $this->alert('success', __('Item deleted successfully.'));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->alert('error', __('Cannot delete this item because it has related records.'));
            } else {
                $this->alert('error', __('An error occurred while deleting the item.'));
            }
        }
    }

    public function deleteSelected(\App\Services\QuotationService $quotationService): void
    {
        abort_if(Gate::denies($this->getGateDelete()), 403);

        try {
            $quotations = Quotation::whereIn('id', $this->selected)->get();
            foreach ($quotations as $quotation) {
                $quotationService->delete($quotation);
            }
            $this->resetSelected();
            $this->alert('success', __('Items deleted successfully.'));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->alert('error', __('Some items cannot be deleted because they have related records.'));
            } else {
                $this->alert('error', __('An error occurred while deleting the items.'));
            }
        }
    }

    protected function getGateDelete(): string
    {
        return 'quotation_delete';
    }
}
