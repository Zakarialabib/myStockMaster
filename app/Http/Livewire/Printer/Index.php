<?php

declare(strict_types=1);

namespace App\Http\Livewire\Printer;

use App\Http\Livewire\WithSorting;
use App\Models\Printer;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    public $printer;

    /** @var array<string> */
    public $listeners = ['showModal', 'editModal', 'refreshIndex'];

    public $showModal = false;

    public $editModal = false;

    /** @var array<array<string>> */
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

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Printer())->orderable;
    }

    public function render()
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
