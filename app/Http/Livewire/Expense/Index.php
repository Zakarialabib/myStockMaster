<?php

declare(strict_types=1);

namespace App\Http\Livewire\Expense;

use App\Exports\ExpenseExport;
use App\Http\Livewire\WithSorting;
use App\Models\Expense;
use App\Models\Warehouse;
use App\Models\ExpenseCategory;
use App\Traits\Datatable;
use App\Domain\Filters\DateFilter;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $expense;

    /** @var array<string> */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal', 'editModal',
        'exportAll', 'downloadAll',
        'delete',
    ];

    public $showModal = false;

    public $editModal = false;

    public $showFilters = false;

    public $startDate;
    public $endDate;
    public $filterType;

    public $listsForFields = [];

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
        'expense.reference'    => 'required|string|max:255',
        'expense.category_id'  => 'required|integer|exists:expense_categories,id',
        'expense.date'         => 'required|date',
        'expense.amount'       => 'required|numeric',
        'expense.details'      => 'nullable|string|max:255',
        'expense.warehouse_id' => 'nullable',
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Expense())->orderable;
        $this->initListsForFields();
        $this->startDate = Expense::orderBy('created_at')->value('created_at');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatedStartDate($value)
    {
        $this->startDate = $value;
    }

    public function updatedEndDate($value)
    {
        $this->endDate = $value;
    }

    public function filterByType($type)
    {
        $this->filterType = $type;
    }

    public function filterType()
    {
        switch ($this->filterType) {
            case 'day':
                $this->startDate = now()->format('Y-m-d');
                $this->endDate = now()->format('Y-m-d');

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
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $this->filterType();

        $filter = new DateFilter();

        $expenses = $filter->filterDate($query, $this->startDate, $this->endDate)->paginate($this->perPage);

        return view('livewire.expense.index', compact('expenses'));
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('expense_delete'), 403);

        Expense::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Expense $expense): void
    {
        abort_if(Gate::denies('expense_delete'), 403);

        $expense->delete();
    }

    public function showModal(Expense $expense): void
    {
        abort_if(Gate::denies('expense_show'), 403);

        $this->expense = Expense::find($expense->id);

        $this->showModal = true;
    }

    public function editModal(Expense $expense): void
    {
        abort_if(Gate::denies('expense_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->expense = Expense::find($expense->id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->expense->save();

        $this->alert('success', __('Expense updated successfully.'));

        $this->emit('refreshIndex');

        $this->editModal = false;
    }

    public function downloadSelected(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_download'), 403);

        return $this->callExport()->forModels($this->selected)->download('expenses.xlsx');
    }

    public function downloadAll(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_download'), 403);

        return $this->callExport()->download('expenses.xlsx');
    }

    public function exportSelected(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_download'), 403);

        return $this->callExport()->forModels($this->selected)->download('expenses.pdf');
    }

    public function exportAll(): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_download'), 403);

        return $this->callExport()->download('expenses.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    protected function initListsForFields()
    {
        $this->listsForFields['expensecategories'] = ExpenseCategory::select('name', 'id')->get();
        $this->listsForFields['warehouses'] = Warehouse::select('name', 'id')->get();
    }

    private function callExport(): ExpenseExport
    {
        return new ExpenseExport();
    }
}
