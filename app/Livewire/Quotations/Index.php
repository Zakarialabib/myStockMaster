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

    public mixed $quotation;

    public string $model = Quotation::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $query = Quotation::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.quotations.index', ['quotations' => $lengthAwarePaginator]);
    }

    #[On('delete')]
    public function delete(\App\Services\QuotationService $quotationService, ?int $id = null): void
    {
        abort_if(Gate::denies($this->getDeleteAbility()), 403);

        try {
            $idToDelete = $id ?? $this->value;
            $quotation = Quotation::query()->findOrFail($idToDelete);
            $quotationService->delete($quotation);
            $this->alert('success', __('Item deleted successfully.'));
        } catch (\Illuminate\Database\QueryException $queryException) {
            if ($queryException->getCode() === '23000') {
                $this->alert('error', __('Cannot delete this item because it has related records.'));
            } else {
                $this->alert('error', __('An error occurred while deleting the item.'));
            }
        }
    }

    public function deleteSelected(\App\Services\QuotationService $quotationService): void
    {
        abort_if(Gate::denies($this->getDeleteAbility()), 403);

        try {
            $quotations = Quotation::query()->whereIn('id', $this->selected)->get();
            foreach ($quotations as $quotation) {
                $quotationService->delete($quotation);
            }

            $this->resetSelected();
            $this->alert('success', __('Items deleted successfully.'));
        } catch (\Illuminate\Database\QueryException $queryException) {
            if ($queryException->getCode() === '23000') {
                $this->alert('error', __('Some items cannot be deleted because they have related records.'));
            } else {
                $this->alert('error', __('An error occurred while deleting the items.'));
            }
        }
    }

    protected function getDeleteAbility(): string
    {
        return 'quotation_delete';
    }
}
