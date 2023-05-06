<?php

declare(strict_types=1);

use App\Models\Adjustment;
use App\Models\Product;
use App\Models\ProductWarehouse;

use function Pest\Livewire\livewire;

it('creates an adjustment', function () {
    // Given
    $reference = 'adjustment-1234';
    $date = '2023-04-23';
    $note = 'This is an adjustment note.';
    $products = [
        [
            'id'         => 1,
            'quantities' => 10,
            'types'      => 'add',
        ],
        [
            'id'         => 2,
            'quantities' => 20,
            'types'      => 'sub',
        ],
    ];

    // When
    livewire('adjustment.create', [
        'reference' => $reference,
        'date'      => $date,
        'note'      => $note,
        'products'  => $products,
    ]);

    // Then
    expect(Adjustment::count())->toBe(1);
    expect(Product::count())->toBe(2);
    expect(ProductWarehouse::count())->toBe(2);

    $adjustment = Adjustment::first();
    expect($adjustment->reference)->toBe($reference);
    expect($adjustment->date)->toBe($date);
    expect($adjustment->note)->toBe($note);

    $product1 = Product::find(1);
    expect($product1->qty)->toBe(10);

    $product2 = Product::find(2);
    expect($product2->qty)->toBe(-20);
});
