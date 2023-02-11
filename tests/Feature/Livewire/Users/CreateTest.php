<?php

declare(strict_types=1);

use App\Http\Livewire\Users\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the user create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.users.create');
});

it('tests the create user validation rules', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('name', 'John doe')
        ->set('phone', '00000000000')
        ->call('create');

    assertDatabaseHas('users', [
        'name'  => 'John doe',
        'phone' => '00000000000',
    ]);
});
