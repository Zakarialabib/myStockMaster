<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Milon\Barcode\Facades\DNS1DFacade;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Barryvdh\DomPDF\Facade\Pdf;

class Barcode extends Component
{
    use LivewireAlert;

    public $product;
    public $quantity;
    public $barcodes;

    protected $listeners = ['productSelected', 'getPdf'];

    public function mount()
    {
        $this->product = '';
        $this->quantity = 0;
        $this->barcodes = [];
    }

    public function render()
    {
        return view('livewire.products.barcode');
    }

    // selecte multiple products without barcode
    public function productSelected(Product $product)
    {
        $this->product = $product;
        $this->quantity = 1;
        $this->barcodes = [];
        // $this->emit('productSelected', $this->product, $this->quantity, $this->barcodes);
    }

    // generate barcodes for selected products
    public function generateBarcodes(Product $product, $quantity)
    {
        if ($quantity > 100) {
            $this->alert('error', __('Max quantity is 100 per barcode generation!'));
        }

        $this->barcodes = [];

        for ($i=0; $i < $this->quantity; $i++) {
            $barcode = DNS1DFacade::getBarCodeSVG($product->code, $product->barcode_symbology, 2, 60, 'black', false);
            array_push($this->barcodes, $barcode);
        }
    }

    public function getPdf()
    {
        // dd($this->barcodes);

        $pdf = PDF::loadView('admin.barcode.print', [
            'barcodes' => $this->barcodes,
            'price' => $this->product->price,
            'name' => $this->product->name,
        ])->output();

        return response()->streamDownload(
            fn () => print($pdf),
            'barcodes-'. $this->product->name .'.pdf'
        );
        // return $pdf->streamDownload('barcodes-'. $this->product->code .'.pdf');
    }

    public function updatedQuantity()
    {
        $this->barcodes = [];
    }
}
