<?php

declare(strict_types=1);

use App\Livewire\Users\Index;
use Livewire\Livewire;

test('the livewire users component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('users.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.users.index');
});
