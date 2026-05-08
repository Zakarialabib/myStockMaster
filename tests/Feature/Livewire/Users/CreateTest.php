<?php

declare(strict_types=1);

use App\Livewire\Users\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the user create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.users.create');
});

it('tests the create user component', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->call('openCreateModal')
        ->set('form.name', 'John Doe')
        ->set('form.phone', '00000000000')
        ->set('form.email', 'user_create_test@example.com')
        ->set('form.password', 'password')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'phone' => '00000000000',
        'email' => 'user_create_test@example.com',
    ]);
});

it('tests the create user component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->call('openCreateModal')
        ->set('form.name', '')
        ->set('form.phone', '')
        ->set('form.email', '')
        ->set('form.password', '')
        ->call('create')
        ->assertHasErrors(['form.name', 'form.phone', 'form.email', 'form.password']);
});
