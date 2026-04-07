<?php

declare(strict_types=1);

namespace App\Livewire\Expense;

use App\Livewire\Forms\ExpenseForm;
use App\Livewire\Utils\WithModels;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\ExpenseService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    public bool $createModal = false;

    public Expense $expense;

    public ExpenseForm $form;

    public mixed $user_id;

    public mixed $cash_register_id;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('expense_create'), 403);

        return view('livewire.expense.create');
    }

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->form->date = date('Y-m-d');
        $this->user_id = auth()->user()->id;

        if (settings()->default_warehouse_id !== null) {
            $this->form->warehouse_id = settings()->default_warehouse_id;
        }

        if ($this->user_id && $this->form->warehouse_id) {
            $cashRegister = CashRegister::query()->where('user_id', $this->user_id)
                ->where('warehouse_id', $this->form->warehouse_id)
                ->where('status', true)
                ->first();

            if ($cashRegister) {
                $this->cash_register_id = $cashRegister->id;
            }
        }

        $this->createModal = true;
    }

    public function create(ExpenseService $expenseService): void
    {
        $this->validate();

        $data = $this->form->all();

        $data['user_id'] = $this->user_id;
        $data['cash_register_id'] = $this->cash_register_id;

        $this->expense = $expenseService->create($data);

        $this->alert('success', __('Expense created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;

        $this->form->reset();
        $this->reset(['user_id', 'cash_register_id']);
    }

    #[Computed]
    public function expenseCategories()
    {
        return ExpenseCategory::query()->select('name', 'id')->get();
    }
}
