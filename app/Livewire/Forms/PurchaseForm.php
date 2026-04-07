<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PurchaseForm extends Form
{
    public ?\App\Models\Purchase $purchase = null;

    #[Validate('required', message: 'Please provide a supplier ID')]
    public int|string|null $supplier_id = null;

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

    #[Validate('required|string|max:50')]
    public int|string|null $status = null;

    #[Validate('nullable|string|max:50')]
    public int|string|null $payment_status = null;

    #[Validate('required|string|max:50')]
    public string $payment_method = 'cash';

    #[Validate('required|date')]
    public ?string $date = null;

    public function setPurchase(\App\Models\Purchase $purchase): void
    {
        $this->purchase = $purchase;
        $this->supplier_id = $purchase->supplier_id;
        $this->warehouse_id = $purchase->warehouse_id;
        $this->tax_percentage = $purchase->tax_percentage;
        $this->discount_percentage = $purchase->discount_percentage;
        $this->shipping_amount = $purchase->shipping_amount / 100;
        $this->total_amount = $purchase->total_amount / 100;
        $this->paid_amount = $purchase->paid_amount / 100;
        $this->note = $purchase->note;
        $this->status = $purchase->status;
        $this->payment_status = $purchase->payment_status;
        $this->payment_method = $purchase->payment_method ?? 'cash';
        $this->date = $purchase->date;
    }
}
