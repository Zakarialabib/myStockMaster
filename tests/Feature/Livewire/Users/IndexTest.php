<?php

declare(strict_types=1);

use App\Livewire\Users\Index;

test('the livewire users component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('users.index'))
        ->assertStatus(200);

    $this->livewire(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.users.index');
});
