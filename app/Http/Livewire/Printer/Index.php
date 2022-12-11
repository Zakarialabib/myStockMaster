<?php

declare(strict_types=1);

namespace App\Http\Livewire\Printer;

use App\Http\Livewire\WithSorting;
use App\Models\Printer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;

    public $printer;

    public int $perPage;

    public $listeners = ['confirmDelete', 'delete', 'showModal', 'editModal', 'refreshIndex'];

    public $showModal;

    public $refreshIndex;

    public $editModal;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public $selectPage;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }

    public function refreshIndex(): void
    {
        $this->resetPage();
    }

    public array $rules = [
        'printer.name'               => 'required|string|max:255',
        'printer.connection_type'    => 'required|string|max:255',
        'printer.capability_profile' => 'required|string|max:255',
        'printer.char_per_line'      => 'required',
        'printer.ip_address'         => 'required|string|max:255',
        'printer.port'               => 'required|string|max:255',
        'printer.path'               => 'required|string|max:255',
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Printer())->orderable;
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('printer_access'), 403);

        $query = Printer::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $printers = $query->paginate($this->perPage);

        return view('livewire.printer.index', compact('printers'));
    }

    public function showModal(Printer $printer): void
    {
        abort_if(Gate::denies('printer_show'), 403);

        $this->printer = $printer;

        $this->showModal = true;
    }

    public function editModal(Printer $printer): void
    {
        abort_if(Gate::denies('printer_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->printer = $printer;

        $this->editModal = true;
    }

    public function update(Printer $printer): void
    {
        abort_if(Gate::denies('printer_edit'), 403);

        $this->validate();

        $this->printer->save();

        $this->editModal = false;

        $this->alert('success', __('Printer updated successfully!'));
    }

    public function delete(Printer $printer): void
    {
        abort_if(Gate::denies('printer_delete'), 403);

        $printer->delete();

        $this->alert('success', __('Printer deleted successfully!'));
    }
}
