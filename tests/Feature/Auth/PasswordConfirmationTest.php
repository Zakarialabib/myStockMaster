<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Livewire;

it('renders the confirm password screen', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get('/confirm-password')->assertOk();

    Livewire::test('pages.auth.confirm-password')
        ->assertStatus(200);
});

it('confirms password with valid credentials', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('pages.auth.confirm-password')
        ->set('password', 'password')
        ->call('confirmPassword')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));
});

it('rejects invalid password confirmation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('pages.auth.confirm-password')
        ->set('password', 'wrong-password')
        ->call('confirmPassword')
        ->assertHasErrors('password')
        ->assertNoRedirect();
});
