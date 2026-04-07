<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Enums\PaymentStatus;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PayDue extends Component
{
    use WithAlert;

    #[Validate('required|numeric|min:0')]
    public mixed $amount;

    #[Validate('required|array')]
    public mixed $selectedSales;

    public mixed $due_amount;

    public mixed $paid_amount;

    public mixed $payment_id;

    public mixed $customer_id;

    public bool $payModal = false;

    public function getSalesCustomerDueProperty()
    {
        return Sale::query()->where('customer_id', $this->customer_id)
            ->where('due_amount', '>', 0)
            ->get();
    }

    #[On('payModal')]
    public function payModal(mixed $customer): void
    {
        $this->payModal = true;
        $this->customer_id = $customer;
    }

    public function makePayment(): void
    {
        $this->validate();

        foreach ($this->selectedSales as $selectedSale) {
            $sale = Sale::query()->findOrFail($selectedSale);
            $dueAmount = $sale->due_amount;
            $paidAmount = min($this->amount, $dueAmount);

            SalePayment::query()->create([
                'date' => date('Y-m-d'),
                'amount' => $paidAmount,
                'sale_id' => $sale->id,
                'payment_id' => $this->payment_id,
                'user_id' => Auth::user()->id,
            ]);

            $sale->update([
                'paid_amount' => ($sale->paid_amount + $paidAmount) * 100,
                'due_amount' => max(0, $dueAmount - $paidAmount) * 100,
                'payment_status' => max(0, $dueAmount - $paidAmount) === 0 ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
            ]);

            $this->amount -= $paidAmount;

            if ($this->amount === 0) {
                break;
            }

            $this->alert('succes', '');
            $this->payModal = false;
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.customers.pay-due');
    }
}
