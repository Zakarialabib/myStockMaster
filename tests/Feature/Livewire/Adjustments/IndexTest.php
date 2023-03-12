<?php

declare(strict_types=1);

use App\Http\Livewire\Adjustment\Index;

it('test adjustment list if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->livewire(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.adjustment.index');
});
