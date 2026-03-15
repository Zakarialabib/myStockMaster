<?php

declare(strict_types=1);

use Livewire\Livewire;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

it('renders the registration screen', function () {
    Livewire::test('pages.auth.register')
        ->assertStatus(200);
});

it('registers a new user', function () {
    Event::fake([Registered::class]);

    Livewire::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('phone', '08000000000')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
    Event::assertDispatched(Registered::class);
});
