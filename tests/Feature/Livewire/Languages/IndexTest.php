<?php

declare(strict_types=1);

use App\Http\Livewire\Language\Index;

test('the livewire language component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('languages.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.language.index');
});
