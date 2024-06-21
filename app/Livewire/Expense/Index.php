<?php

declare(strict_types=1);

namespace App\Livewire\Expense;

use App\Exports\ExpenseExport;
use App\Imports\ExpenseImport;
use App\Livewire\Utils\Datatable;
use App\Models\Expense;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $expense;

    public $showModal = false;

    public $showFilters = false;

    public $startDate;

    public $endDate;

    public $filterType;

    public $file;

    public $importModal = false;

    public $model = Expense::class;

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
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
        abort_if(Gate::denies('expense_access'), 403);

        $query = Expense::with(['category', 'user', 'warehouse'])
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $expenses = $query->paginate($this->perPage);

        return view('livewire.expense.index', ['expenses' => $expenses]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('expense_delete'), 403);

        Expense::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Expense $expense): void
    {
        // abort_if(Gate::denies('expense_delete'), 403);

        $expense->delete();
    }

    public function showModal($id): void
    {
        // abort_if(Gate::denies('expense_show'), 403);

        $this->expense = Expense::find($id);

        $this->showModal = true;
    }

    public function downloadSelected(): StreamedResponse|Response
    {
        // abort_if(Gate::denies('expense download'), 403);

        return $this->callExport()->forModels($this->selected)->download('expenses.xlsx');
    }

    public function downloadAll(): StreamedResponse|Response
    {
        // abort_if(Gate::denies('expense download'), 403);

        return $this->callExport()->download('expenses.xlsx');
    }

    public function exportSelected(): StreamedResponse|Response
    {
        // abort_if(Gate::denies('expense download'), 403);

        return $this->callExport()->forModels($this->selected)->download('expenses.pdf');
    }

    public function exportAll(): StreamedResponse|Response
    {
        // abort_if(Gate::denies('expense download'), 403);

        return $this->callExport()->download('expenses.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): ExpenseExport
    {
        return new ExpenseExport();
    }

    public function importExcel(): void
    {
        abort_if(Gate::denies('expense_access'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        Excel::import(new ExpenseImport(), $this->file);

        $this->importModal = false;

        $this->alert('success', __('Expense imported successfully.'));
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('expenses_import_sample.xls');
    }

    public function deleteModal($expense): void
    {
        $confirmationMessage = __('Are you sure you want to delete this expense? if something happens you can be recover it.');

        $this->confirm($confirmationMessage, [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);

        $this->expense = $expense;
    }
}
