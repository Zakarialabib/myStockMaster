<?php

declare(strict_types=1);

use App\Http\Livewire\Categories\Index;

test('the livewire category component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('product-categories.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.categories.index');
});
