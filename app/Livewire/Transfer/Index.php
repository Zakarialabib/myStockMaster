<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Livewire\Utils\Datatable;
use App\Models\Transfer;
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

    public $transfer;

    public $model = Transfer::class;

    public function render()
    {
        abort_if(Gate::denies('transfer_access'), 403);

        $query = Transfer::with('transferDetails')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
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
