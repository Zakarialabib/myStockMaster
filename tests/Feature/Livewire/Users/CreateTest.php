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

it('tests the create user component', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('user.name', 'John Doe')
        ->set('user.phone', '00000000000')
        ->set('user.email', 'admin@admin.com')
        ->set('user.password', 'password')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'     => 'John Doe',
        'phone'    => '00000000000',
        'email'    => 'admin@admin.com',
        'password' => 'password',
    ]);
});

it('tests the create user component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('user.name', '')
        ->set('user.phone', '')
        ->set('user.email', '')
        ->set('user.password', '')
        ->call('create')
        ->assertHasErrors(
            ['user.name' => 'required'],
            ['user.phone'    => 'required'],
            ['user.email'    => 'required'],
            ['user.password' => 'required']
        );
});
