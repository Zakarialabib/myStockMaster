<?php

declare(strict_types=1);

namespace App\Livewire\CashRegister;

use App\Livewire\Utils\Datatable;
use App\Models\CashRegister;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $cashRegister;

    public $showFilters = false;

    public $startDate;

    public $endDate;

    public $filterType;

    public $model = CashRegister::class;

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->format('Y-m-d');
        $this->endDate = now()->endOfYear()->format('Y-m-d');
    }

    public function filterByType($type): void
    {
        switch ($type) {
            case 'day':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');

                break;
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');

                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');

                break;
        }
    }

    public function render()
    {
        // abort_if(Gate::denies('cashRegister_access'), 403);

        $query = CashRegister::with(['user', 'warehouse'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $cashRegisters = $query->paginate($this->perPage);

        return view('livewire.cash-register.index', ['cashRegisters' => $cashRegisters]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('cashRegister_delete'), 403);

        CashRegister::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(CashRegister $cashRegister): void
    {
        abort_if(Gate::denies('cashRegister_delete'), 403);

        $cashRegister->delete();
    }
}
