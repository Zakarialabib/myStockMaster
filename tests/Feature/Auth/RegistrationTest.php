<?php

declare(strict_types=1);

use Livewire\Livewire;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

it('renders the registration screen', function () {
    Livewire::test('pages.auth.register')
        ->assertSuccessful();
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

it('validates registration input', function (string $field, string $value, string $rule) {
    Livewire::test('pages.auth.register')
        ->set($field, $value)
        ->call('register')
        ->assertHasErrors([$field => $rule]);
})->with([
    'name required' => ['name', '', 'required'],
    'email required' => ['email', '', 'required'],
    'email invalid' => ['email', 'not-an-email', 'email'],
    'phone required' => ['phone', '', 'required'],
    'password required' => ['password', '', 'required'],
    'password too short' => ['password', '123', 'min'],
    'password confirmation' => ['password_confirmation', 'mismatch', 'same'],
]);
