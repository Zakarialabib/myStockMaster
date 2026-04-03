<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Livewire\Utils\Datatable;
use App\Models\Transfer;
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

    public $transfer;

    public $model = Transfer::class;

    public function render()
    {
        abort_if(Gate::denies('transfer_access'), 403);

        $query = Transfer::with('transferDetails')
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $transfers = $query->paginate($this->perPage);

        return view('livewire.transfer.index', ['transfers' => $transfers]);
    }

    public function deleteSelected(): void
    {
        // abort_if(Gate::denies('transfer_delete'), 403);

        Transfer::whereIn('id', $this->selected)->delete();

        $this->resetSelected();

        $this->alert('success', __('Transfer deleted successfully.'));
    }

    #[On('delete')]
    public function delete(Transfer $transfer): void
    {
        abort_if(Gate::denies('transfer_delete'), 403);

        $transfer->delete();

        $this->alert('success', __('Transfer deleted successfully.'));
    }
}
