<?php

declare(strict_types=1);

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceTemplate extends Component
{
    public $invoiceTemplate;
    public $invoiceData;
    public $templatePreview;

    public function mount()
    {
        $this->invoiceTemplate = config('invoices.default_template');
        $this->invoiceData = config('invoices.templates.'.$this->invoiceTemplate);
        $this->templatePreview = view("invoice_templates.$this->invoiceTemplate", $this->invoiceData)->render();
    }

    public function updatedInvoiceTemplate()
    {
        $this->invoiceData = config('invoices.templates.'.$this->invoiceTemplate);
        $this->templatePreview = view("invoice_templates.$this->invoiceTemplate", $this->invoiceData)->render();
    }

    public function downloadInvoice()
    {
        $pdf = PDF::loadView("invoice_templates.$this->invoiceTemplate", $this->invoiceData);

        return $pdf->download('invoice.pdf');
    }

    public function updateInvoiceData($key, $value)
    {
        $this->invoiceData[$key] = $value;
    }

    public function render()
    {
        $templates = array_keys(config('invoices.templates'));

        return view('livewire.settings.invoice-template', compact('templates'));
    }
}
