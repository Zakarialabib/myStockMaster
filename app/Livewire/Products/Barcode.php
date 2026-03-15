<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Actions\Products\GenerateBarcodesAction;
use App\Livewire\Utils\WithModels;
use App\Models\ProductWarehouse;
use App\Traits\WithAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class Barcode extends Component
{
    use WithModels;
    use WithAlert;

    public $warehouse_id;

    #[Validate([
        'products.*.quantity' => 'required|integer|min:1|max:100',
        'products.*.barcodeSize' => 'required|in:small,medium,large,extra,huge',
    ])]
    public array $products = [];

    public array $barcodes = [];

    public string $paperSize = 'A4';

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }

    #[On('productSelected')]
    public function productSelected($id): void
    {
        $productWarehouse = ProductWarehouse::where('product_id', $id)
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        if ($productWarehouse) {
            $this->products[] = [
                'id'                => $productWarehouse->product_id,
                'name'              => $productWarehouse->product->name,
                'code'              => $productWarehouse->product->code,
                'price'             => $productWarehouse->price,
                'quantity'          => 1,
                'barcode_symbology' => $productWarehouse->product->barcode_symbology,
                'barcodeSize'       => 'medium',
            ];
        }
    }

    public function generateBarcodes(): void
    {
        if (empty($this->products)) {
            $this->alert('error', __('Please select at least one product to generate barcodes!'));

            return;
        }

        $this->validate();

        $this->barcodes = app(GenerateBarcodesAction::class)($this->products);
    }

    public function downloadBarcodes(): StreamedResponse
    {
        $data = [
            'barcodes' => $this->barcodes,
        ];

        $stylesheet = file_get_contents(public_path('print/bootstrap.min.css'));

        $pdf = PDF::loadView('pdf.bardcode-print', $data, [
            'format' => $this->paperSize,
        ]);

        $pdf->getMpdf()->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        return response()->streamDownload(static function () use ($pdf): void {
            echo $pdf->output();
        }, 'barcodes-'.date('Y-m-d').'.pdf');
    }

    public function deleteProduct($productId): void
    {
        $index = null;

        foreach ($this->products as $key => $product) {
            if ($product['id'] === $productId) {
                $index = $key;

                break;
            }
        }

        if (! is_null($index)) {
            unset($this->products[$index]);
            $this->products = array_values($this->products);
        }
    }

    public function render()
    {
        return view('livewire.products.barcode');
    }
}
