<?php

declare(strict_types=1);

use App\Livewire\Permission\Index;

test('the livewire permission component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('permissions.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.permission.index');
});
