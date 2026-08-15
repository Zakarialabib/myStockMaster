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
            // Products without a configured symbology (nullable column) must not
            // crash milon/barcode, which treats a false/empty type as "unknown"
            // and then fails accessing the (false) barcode array. Default to C39.
            $symbology = ! empty($product['barcode_symbology']) ? $product['barcode_symbology'] : 'C39';

            for ($i = 0; $i < $quantity; $i++) {
                $barcode = DNS1DFacade::getBarCodeSVG(
                    $product['code'],
                    $symbology,
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
