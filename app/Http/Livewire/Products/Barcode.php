<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\ProductWarehouse; // Import ProductWarehouse model instead of Product model
use App\Models\Product;
use App\Models\Warehouse;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Milon\Barcode\Facades\DNS1DFacade;
use PDF;

class Barcode extends Component
{
    use LivewireAlert;

    public $warehouse_id;
    public $products = [];
    public $barcodes = [];
    public $paperSize = 'A4';

    protected $listeners = ['productSelected'];

    protected $rules = [
        'products.*.quantity'    => 'required|integer|min:1|max:100',
        'products.*.barcodeSize' => 'required|in:small,medium,large,extra,huge',
    ];

    public function updatedWarehouseId($value)
    {
        $this->warehouse_id = $value;
        $this->emit('warehouseSelected', $this->warehouse_id);
    }

    public function productSelected($product): void
    {
        $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        if ($productWarehouse) {
            array_push($this->products, [
                'id'                => $productWarehouse->product_id,
                'name'              => $productWarehouse->product->name,
                'code'              => $productWarehouse->product->code,
                'price'             => $productWarehouse->price,
                'quantity'          => 1,
                'barcode_symbology' => $productWarehouse->product->barcode_symbology,
                'barcodeSize'       => 1,
            ]);
        }
    }

    public function generateBarcodes()
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

            for ($i = 0; $i < $quantity; $i++) {
                $barcode = DNS1DFacade::getBarCodeSVG($product['code'], $product['barcode_symbology'], $product['barcodeSize'], 60, 'black', false);

                array_push($this->barcodes, ['barcode' => $barcode, 'name' => $name, 'price' => $price]);
            }
        }
    }

    public function downloadBarcodes()
    {
        $data = [
            'barcodes' => $this->barcodes,
        ];

        $stylesheet = file_get_contents(public_path('print/bootstrap.min.css'));

        $pdf = PDF::loadView('admin.barcode.print', $data, [
            'format' => $this->paperSize,
        ]);

        $pdf->getMpdf()->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        return $pdf->download('barcodes-'.date('Y-m-d').'.pdf');
    }

    public function deleteProduct($productId)
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

    public function getWarehousesProperty()
    {
        return Warehouse::pluck('name', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.products.barcode');
    }
}
