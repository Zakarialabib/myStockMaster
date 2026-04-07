<?php

declare(strict_types=1);

namespace App\Livewire\Printer;

use App\Livewire\Utils\Datatable;
use App\Models\Printer;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]

class Index extends Component
{
    use Datatable;
    use WithAlert;

    #[Validate([
        'printer.name' => 'required|string|min:3|max:255',
        'printer.connection_type' => 'required|string|max:255',
        'printer.capability_profile' => 'required|string|max:255',
        'printer.char_per_line' => 'required',
        'printer.ip_address' => 'required|string|max:255',
        'printer.port' => 'required|string|max:255',
        'printer.path' => 'required|string|max:255',
    ])]
    public mixed $printer;

    public bool $showModal = false;

    public bool $openModal = false;

    public string $model = Printer::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('printer_access'), 403);

        $query = Printer::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.printer.index', ['printers' => $lengthAwarePaginator]);
    }

    #[On('showModal')]
    public function showModal(Printer $printer): void
    {
        abort_if(Gate::denies('printer_show'), 403);

        $this->printer = $printer;

        $this->showModal = true;
    }

    public function editModal(Printer $printer): void
    {
        abort_if(Gate::denies('printer_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->printer = $printer;

        $this->openModal = true;
    }

    public function update(): void
    {
        abort_if(Gate::denies('printer_update'), 403);

        $this->validate();

        $this->printer->save();

        $this->openModal = false;

        $this->alert('success', __('Printer updated successfully!'));
    }

    public function delete(Printer $printer): void
    {
        abort_if(Gate::denies('printer_delete'), 403);

        $printer->delete();

        $this->alert('success', __('Printer deleted successfully!'));
    }
}
