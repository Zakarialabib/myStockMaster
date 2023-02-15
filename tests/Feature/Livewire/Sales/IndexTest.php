<?php

declare(strict_types=1);

use App\Http\Livewire\Sales\Index;

it('test sales list if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->livewire(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.sales.index');
});
