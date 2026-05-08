<?php

declare(strict_types=1);

use App\Livewire\Sales\Index;
use Livewire\Livewire;

it('test sales list if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.sales.index');
});
