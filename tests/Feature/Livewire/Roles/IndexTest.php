<?php

declare(strict_types=1);

use App\Livewire\Role\Index;

test('the livewire role component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('roles.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.role.index');
});
