<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SaleForm extends Form
{
    public ?\App\Models\Sale $sale = null;

    #[Validate('required', message: 'Please provide a customer ID')]
    public int|string|null $customer_id = null;

    #[Validate('required', message: 'Please provide a warehouse ID')]
    public int|string|null $warehouse_id = null;

    #[Validate('required|integer|min:0|max:100', message: ['required' => 'Please provide a tax percentage'])]
    public int $tax_percentage = 0;

    #[Validate('required|integer|min:0|max:100', message: ['required' => 'Please provide a discount percentage'])]
    public int $discount_percentage = 0;

    #[Validate('nullable|numeric', message: ['numeric' => 'Shipping amount must be a numeric value'])]
    public float|int $shipping_amount = 0;

    #[Validate('required|numeric', message: ['required' => 'Please provide a total amount'])]
    public float|int $total_amount = 0;

    #[Validate('nullable|numeric', message: ['numeric' => 'Paid amount must be a numeric value'])]
    public float|int $paid_amount = 0;

    #[Validate('nullable|string|max:1000', message: ['max' => 'Note must not exceed 1000 characters'])]
    public ?string $note = null;

    #[Validate('required|integer|max:255')]
    public int|string|null $status = null;

    #[Validate('nullable|integer|max:255')]
    public int|string|null $payment_status = null;

    #[Validate('required|string|max:255')]
    public string $payment_method = 'cash';

    #[Validate('required|date')]
    public ?string $date = null;

    public function setSale(\App\Models\Sale $sale): void
    {
        $this->sale = $sale;
        $this->customer_id = $sale->customer_id;
        $this->warehouse_id = $sale->warehouse_id;
        $this->tax_percentage = $sale->tax_percentage;
        $this->discount_percentage = $sale->discount_percentage;
        $this->shipping_amount = $sale->shipping_amount / 100;
        $this->total_amount = $sale->total_amount / 100;
        $this->paid_amount = $sale->paid_amount / 100;
        $this->note = $sale->note;
        $this->status = $sale->status;
        $this->payment_status = $sale->payment_status;
        $this->payment_method = $sale->payment_method ?? 'cash';
        $this->date = $sale->date;
    }
}
