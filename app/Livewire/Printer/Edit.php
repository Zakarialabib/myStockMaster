<?php

declare(strict_types=1);

namespace App\Livewire\Printer;

use App\Enums\CapabilityProfile;
use App\Enums\ConnectionType;
use App\Models\Printer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

    public $editModal;

    public $printer;

    public $capability_profiles;

    public $connection_types;

    public function rules(): array
    {
        return [
            'printer.name' => 'required|string|min:max:255',
            'printer.connection_type' => ['required', 'string', Rule::enum(ConnectionType::class)],
            'printer.capability_profile' => ['required', 'string', Rule::enum(CapabilityProfile::class)],
            'printer.char_per_line' => 'required|integer|min:1',
            'printer.ip_address' => 'required|ip',
            'printer.port' => 'required|integer|between:1,65535',
            'printer.path' => 'required|string|max:255',
        ];
    }

    public function render()
    {
        abort_if(Gate::denies('printer_update'), 403);

        return view('livewire.printer.create');
    }

    #[On('editPrinter')]
    public function editPrinter(Printer $printer): void
    {
        $this->printer = $printer;

        $this->resetErrorBag();

        $this->resetValidation();

        $this->capability_profiles = Printer::capabilityProfiles();
        $this->connection_types = Printer::connectionTypes();

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->printer->save();

        $this->alert('success', __('Printer updated successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
