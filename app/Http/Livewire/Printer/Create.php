<?php

namespace App\Http\Livewire\Printer;

use App\Models\Printer;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createPrinter'];

    public $createPrinter;

    public array $rules = [
        'printer.name' => 'required|string|max:255',
        'printer.connection_type' => 'required|string|max:255',
        'printer.capability_profile' => 'required|string|max:255',
        'printer.char_per_line' => 'required',
        'printer.ip_address' => 'required|string|max:255',
        'printer.port' => 'required|string|max:255',
        'printer.path' => 'required|string|max:255',
    ];

    public function mount(Printer $printer)
    {
        $this->printer = $printer;
    }

    public function render()
    {
        abort_if(Gate::denies('printer_create'), 403);

        return view('livewire.printer.create');
    }

    public function createPrinter()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $capability_profiles = Printer::capability_profiles();
        $connection_types = Printer::connection_types();

        $this->createPrinter = true;
    }

    public function create()
    {
        $this->validate();

        $this->printer->save();

        $this->alert('success', __('Printer created successfully.'));

        $this->emit('refreshIndex');

        $this->createPrinter = false;

    }
}
