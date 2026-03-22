<?php

declare(strict_types=1);

use App\Livewire\Products\Index;
use Livewire\Livewire;

it('the component can render', function () {
    $this->withoutExceptionHandling();

    $this->loginAsAdmin();

    Livewire::test(Index::class)
        ->assertSuccessful()
        ->assertViewIs('livewire.products.index');
});
