<?php

declare(strict_types=1);

namespace App\Actions\Products;

use Milon\Barcode\Facades\DNS1DFacade;

final class GenerateBarcodesAction
{
    public function __invoke(iterable $products): array
    {
        $barcodes = [];

        foreach ($products as $product) {
            $quantity = (int) $product['quantity'];

            for ($i = 0; $i < $quantity; $i++) {
                $barcode = DNS1DFacade::getBarCodeSVG(
                    $product['code'],
                    $product['barcode_symbology'],
                    $this->barcodeScale($product['barcodeSize']),
                    60,
                    'black',
                    false
                );

                $barcodes[] = [
                    'barcode' => $barcode,
                    'name' => $product['name'],
                    'price' => $product['price'],
                ];
            }
        }

        return $barcodes;
    }

    private function barcodeScale(string $size): float
    {
        return match ($size) {
            'small' => 1.0,
            'medium' => 1.5,
            'large' => 2.0,
            'extra' => 2.5,
            'huge' => 3.0,
            default => 1.5,
        };
    }
}
