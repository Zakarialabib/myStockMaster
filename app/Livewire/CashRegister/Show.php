<?php

declare(strict_types=1);

namespace App\Livewire\CashRegister;

use App\Enums\SaleStatus;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Traits\WithAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public mixed $cashRegister;

    public mixed $total_sale_amount;

    public mixed $total_payment;

    public mixed $cash_payment;

    public mixed $cheque_payment;

    public mixed $total_sale_return;

    public mixed $total_expense;

    public mixed $total_cash;

    #[On('showModal')]
    public function showModal(int|string $id): void
    {
        $this->cashRegister = CashRegister::query()->find($id);

        $this->total_sale_amount = Sale::query()->where([
            ['cash_register_id', $this->cashRegister->id],
            ['status', SaleStatus::COMPLETED],
        ])->sum('total_amount') / 100;

        $this->total_payment = SalePayment::query()->where('cash_register_id', $this->cashRegister->id)
            ->sum('amount') / 100;

        $this->cash_payment = SalePayment::query()->where([
            ['cash_register_id', $this->cashRegister->id],
            ['payment_method', 'Cash'],
        ])->sum('amount') / 100;

        $this->cheque_payment = SalePayment::query()->where([
            ['cash_register_id', $this->cashRegister->id],
            ['payment_method', 'Cheque'],
        ])->sum('amount') / 100;

        $this->total_sale_return = SaleReturn::query()->where('cash_register_id', $this->cashRegister->id)
            ->sum('total_amount') / 100;

        $this->total_expense = Expense::query()->where('cash_register_id', $this->cashRegister->id)
            ->sum('amount') / 100;

        $this->total_cash = ($this->cashRegister->cash_in_hand / 100) + $this->total_payment - ($this->total_sale_return + $this->total_expense);

        $this->showModal = true;
    }

    public function close(): void
    {
        $this->cashRegister->status = false;

        $this->cashRegister->save();

        $this->alert('success', __('Cash register closed successfully'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // abort_if(Gate::denies('cashRegister_show'), 403);

        return view('livewire.cash-register.show');
    }
}
