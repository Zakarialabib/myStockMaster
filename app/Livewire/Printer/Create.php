<?php

declare(strict_types=1);

namespace App\Livewire\Printer;

use App\Models\Printer;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    public bool $createPrinter = false;

    #[Validate([
        'printer.name' => 'required|string|min:3|max:255',
        'printer.connection_type' => 'required|string|max:255',
        'printer.capability_profile' => 'required|string|max:255',
        'printer.char_per_line' => 'required',
        'printer.ip_address' => 'required|string|max:255',
        'printer.port' => 'required|string|max:255',
        'printer.path' => 'required|string|max:255',
    ])]
    public $printer;

    public array $capability_profiles = [];

    public array $connection_types = [];

    public function mount(Printer $printer): void
    {
        $this->printer = $printer;
    }

    public function render()
    {
        abort_if(Gate::denies('printer_create'), 403);

        return view('livewire.printer.create');
    }

    #[On('createPrinter')]
    public function openModal(): void
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

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createPrinter = false;
    }
}
