<?php

declare(strict_types=1);

use App\Livewire\Adjustment\Index;
use Livewire\Livewire;

it('test adjustment list if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.adjustment.index');
});
