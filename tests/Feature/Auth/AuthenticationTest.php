<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Livewire;

it('renders the login screen', function () {
    $this->get('/login')->assertSuccessful();

    Livewire::test('pages.auth.login')
        ->assertSuccessful();
});

it('authenticates users from the login screen', function () {
    $user = User::factory()->create();

    Livewire::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

it('rejects invalid login password', function () {
    $user = User::factory()->create();

    Livewire::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password')
        ->call('login')
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
});
