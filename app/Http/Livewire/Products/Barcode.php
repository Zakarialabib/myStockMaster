<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Milon\Barcode\Facades\DNS1DFacade;
use PDF;
use Symfony\Component\HttpFoundation\Response;

class Barcode extends Component
{
    use LivewireAlert;

    public $product;
    public $products;
    public $quantity;
    public $barcodes;
    public $size;

    protected $listeners = ['productSelected', 'getPdf'];

    public function mount(): void
    {
        $this->products = [];
        $this->product = '';
        $this->quantity = 0;
        $this->barcodes = [];
        $this->size = 'medium';
    }

    public function productSelected($product): void
    {
        $this->products = Product::find($product);
        $this->quantity = 1;
        $this->barcodes = [];
        $this->generateBarcodes($product, $this->quantity);
    }

    public function generateBarcodes($product, $quantity): void
    {
        if ($quantity > 100) {
            $this->alert('error', __('Max quantity is 100 per barcode generation!'));
        }

        $this->barcodes = [];

        switch ($this->size) {
            case 'small':
                $sizeValue = 1;

                break;
            case 'medium':
                $sizeValue = 2;

                break;
            case 'large':
                $sizeValue = 3;

                break;
            case 'extra-large':
                $sizeValue = 4;

                break;
            case 'huge':
                $sizeValue = 5;

                break;
            default:
                $sizeValue = 2;
        }

        for ($i = 0; $i < $this->quantity; $i++) {
            $barcode = DNS1DFacade::getBarCodeSVG($product['code'], $product['barcode_symbology'], $sizeValue, 60, 'black', false);
            array_push($this->barcodes, $barcode);
        }
    }

    public function updatedQuantity(): void
    {
        $this->barcodes = [];

        foreach ($this->products as $product) {
            $this->generateBarcodes($product, $this->quantity);
        }
    }

    public function getPdf(): Response
    {
        $data = [
            'barcodes' => $this->barcodes,
            'products' => $this->products,
        ];

        $pdf = PDF::loadView('admin.barcode.print', $data);

        return $pdf->stream('barcodes-'.date('Y-m-d').'.pdf');
    }

    public function render()
    {
        return view('livewire.products.barcode');
    }
}
