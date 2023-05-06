<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Milon\Barcode\Facades\DNS1DFacade;
use PDF;

class Barcode extends Component
{
    use LivewireAlert;

    public $product;
    public $products = [];
    public $quantity;
    public $barcodes = [];
    public $barcodeSize;
    public $paperSize = 'A4';

    protected $listeners = ['productSelected'];

    protected $rules = [
        'products.*.quantity'    => 'required|integer|min:1|max:100',
        'products.*.barcodeSize' => 'required|in:small,medium,large,extra,huge',
    ];

    public function mount(): void
    {
        $this->product = null;
    }

    public function productSelected($product): void
    {
        $product = Product::find($product['id']);

        $index = $this->findProductIndex($product->id);

        if ($index === false) {
            array_push($this->products, [
                'id'                => $product->id,
                'name'              => $product->name,
                'code'              => $product->code,
                'price'             => $product->price,
                'quantity'          => 1,
                'barcode_symbology' => $product->barcode_symbology,
                'barcodeSize'       => 1,
            ]);
        }
    }

    public function findProductIndex($productId)
    {
        foreach ($this->products as $index => $product) {
            if ($product['id'] === $productId) {
                return $index;
            }
        }

        return false;
    }

    public function updatedQuantity($quantity, $productId)
    {
        $index = $this->findProductIndex($productId);

        $this->products[$index]['quantity'] = $quantity;
    }

    public function updatedBarcodeSize($barcodeSize, $productId)
    {
        $index = $this->findProductIndex($productId);

        $this->products[$index]['barcodeSize'] = $barcodeSize;
    }

    public function generateBarcodes(): void
    {
        if (empty($this->products)) {
            $this->alert('error', __('Please select at least one product to generate barcodes!'));

            return;
        }

        $this->barcodes = [];

        foreach ($this->products as $key => $product) {
            $quantity = $product['quantity'];
            $name = $product['name'];
            $price = $product['price'];

            if ($quantity > 100) {
                $this->alert('error', __('Max quantity is 100 per barcode generation for product :name!', ['name' => $name]));

                continue;
            }
            // dd($product);

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

    public function render()
    {
        return view('livewire.products.barcode');
    }
}
