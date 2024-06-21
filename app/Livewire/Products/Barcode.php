<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Livewire\Utils\WithModels;
use App\Models\ProductWarehouse;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Milon\Barcode\Facades\DNS1DFacade;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Barcode extends Component
{
    use LivewireAlert;
    use WithModels;

    public $warehouse_id;

    public $products = [];

    public $barcodes = [];

    public $paperSize = 'A4';

    protected $rules = [
        'products.*.quantity'    => 'required|integer|min:1|max:100',
        'products.*.barcodeSize' => 'required|in:small,medium,large,extra,huge',
    ];

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
                'barcodeSize'       => 1,
            ];
        }
    }

    public function generateBarcodes(): void
    {
        if (empty($this->products)) {
            $this->alert('error', __('Please select at least one product to generate barcodes!'));

            return;
        }

        $this->barcodes = [];

        foreach ($this->products as  $product) {
            $quantity = $product['quantity'];
            $name = $product['name'];
            $price = $product['price'];

            if ($quantity > 100) {
                $this->alert('error', __('Max quantity is 100 per barcode generation for product :name!', ['name' => $name]));

                continue;
            }

            for ($i = 0; $i < $quantity; ++$i) {
                $barcode = DNS1DFacade::getBarCodeSVG($product['code'], $product['barcode_symbology'], $product['barcodeSize'], 60, 'black', false);

                $this->barcodes[] = ['barcode' => $barcode, 'name' => $name, 'price' => $price];
            }
        }
    }

    public function downloadBarcodes()
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

        if ( ! is_null($index)) {
            unset($this->products[$index]);
            $this->products = array_values($this->products); // Reset array keys
        }
    }

    public function render()
    {
        return view('livewire.products.barcode');
    }
}
