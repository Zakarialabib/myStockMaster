<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

it('renders the forgot password screen', function () {
    $this->get('/forgot-password')->assertOk();

    Livewire::test('pages.auth.forgot-password')
        ->assertStatus(200);
});

it('requests a reset password link', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

it('renders the reset password screen from token', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $this->get('/reset-password/' . $notification->token)->assertOk();

        Livewire::test('pages.auth.reset-password', ['token' => $notification->token])
            ->assertStatus(200);

        return true;
    });
});

it('resets password with a valid token', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        Livewire::test('pages.auth.reset-password', ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('resetPassword')
            ->assertHasNoErrors()
            ->assertRedirect('/login');

        return true;
    });
});
