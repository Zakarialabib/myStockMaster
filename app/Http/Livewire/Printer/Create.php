<?php

declare(strict_types=1);

namespace App\Http\Livewire\Printer;

use App\Models\Printer;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createPrinter'];

    public $createPrinter;

    public $printer;
    public $capability_profiles;
    public $connection_types;

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

    public function mount(Printer $printer): void
    {
        $this->printer = $printer;
    }

    public function render()
    {
        abort_if(Gate::denies('printer_create'), 403);

        return view('livewire.printer.create');
    }

    public function createPrinter(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->capability_profiles = Printer::capabilityProfiles();
        $this->connection_types = Printer::connectionTypes();

        $this->createPrinter = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->printer->save();

        $this->alert('success', __('Printer created successfully.'));

        $this->emit('refreshIndex');

        $this->createPrinter = false;
    }
}
