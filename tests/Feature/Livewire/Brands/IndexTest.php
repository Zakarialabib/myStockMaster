<?php

declare(strict_types=1);

use App\Livewire\Brands\Index;
use Livewire\Livewire;

test('the livewire brand component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('brands.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.brands.index');
});
