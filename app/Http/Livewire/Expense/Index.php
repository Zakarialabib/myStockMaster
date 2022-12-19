<?php

declare(strict_types=1);

namespace App\Http\Livewire\Expense;

use App\Exports\ExpenseExport;
use App\Http\Livewire\WithSorting;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

    /** @var mixed */
    public $expense;

    public int $perPage;

    public $selectPage;

    /** @var string[] */
    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal', 'editModal',
    ];

    public $showModal = false;

    public $editModal = false;

    public $refreshIndex;

    public $export;
    /** @var array */
    public array $orderable;

    /** @var string */
    public string $search = '';

    /** @var array */
    public array $selected = [];

    public bool $showFilters = false;

    public array $listsForFields = [];

    /** @var array */
    public array $paginationOptions;

    /** @var string[][] */
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

    public array $rules = [
        'expense.reference'    => 'required|string|max:255',
        'expense.category_id'  => 'required|integer|exists:expense_categories,id',
        'expense.date'         => 'required|date',
        'expense.amount'       => 'required|numeric',
        'expense.details'      => 'nullable|string|max:255',
        'expense.user_id'      => '',
        'expense.warehouse_id' => '',
    ];

    public function mount(): void
    {
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Expense())->orderable;
        $this->initListsForFields();
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('expense_access'), 403);

        $query = Expense::with(['category', 'user', 'warehouse'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $expenses = $query->paginate($this->perPage);

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

    public function exportAll(Expense $expense): BinaryFileResponse
    {
        abort_if(Gate::denies('expense_download'), 403);

        return $this->callExport()->forModels($expense)->download('expenses.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    private function callExport(): ExpenseExport
    {
        return (new ExpenseExport());
    }

    protected function initListsForFields()
    {
        $this->listsForFields['expensecategories'] = ExpenseCategory::pluck('name', 'id')->toArray();
    }
}
