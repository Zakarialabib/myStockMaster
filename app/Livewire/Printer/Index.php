<?php

declare(strict_types=1);

namespace App\Livewire\Printer;

use App\Livewire\Utils\Datatable;
use App\Models\Printer;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use LivewireAlert;
    use Datatable;

    public $printer;

    /** @var array<string> */
    public $listeners = ['showModal', 'openModal', 'refreshIndex'];

    public $showModal = false;

    public $openModal = false;

    /** @var array */
    protected $rules = [
        'printer.name'               => 'required|string|min:3|max:255',
        'printer.connection_type'    => 'required|string|max:255',
        'printer.capability_profile' => 'required|string|max:255',
        'printer.char_per_line'      => 'required',
        'printer.ip_address'         => 'required|string|max:255',
        'printer.port'               => 'required|string|max:255',
        'printer.path'               => 'required|string|max:255',
    ];

    public $model = Printer::class;

    public function render()
    {
        abort_if(Gate::denies('printer_access'), 403);

        $query = Printer::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $printers = $query->paginate($this->perPage);

        return view('livewire.printer.index', ['printers' => $printers]);
    }

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

    public function update(Printer $printer): void
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
