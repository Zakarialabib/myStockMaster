<?php

declare(strict_types=1);

namespace App\Livewire\Printer;

use App\Livewire\Utils\Datatable;
use App\Models\Printer;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Index extends Component
{
    use LivewireAlert;
    use Datatable;

    public $printer;

    public $showModal = false;

    public function testConnection(Printer $printer): void
    {
        // Implement the logic to test the printer connection
        // This is a placeholder and should be replaced with actual implementation
        $success = true; // Assume success for now

        if ($success) {
            $this->alert('success', __('Printer connection successful!'));
        } else {
            $this->alert('error', __('Printer connection failed. Please check the settings.'));
        }
    }

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

    public function delete(Printer $printer): void
    {
        abort_if(Gate::denies('printer_delete'), 403);

        $printer->delete();

        $this->alert('success', __('Printer deleted successfully!'));
    }
}
