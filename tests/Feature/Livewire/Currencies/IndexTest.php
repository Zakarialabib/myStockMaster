<?php

declare(strict_types=1);

use App\Http\Livewire\Currency\Index;

test('the livewire currency component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('currencies.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.currency.index');
});
