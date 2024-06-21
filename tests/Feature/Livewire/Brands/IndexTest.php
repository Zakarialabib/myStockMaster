<?php

declare(strict_types=1);

use App\Livewire\Brands\Index;

test('the livewire brand component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('brands.index'))
        ->assertStatus(200);

    $this->livewire(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.brands.index');
});
