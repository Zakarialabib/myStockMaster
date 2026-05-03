<?php

declare(strict_types=1);

use App\Livewire\Role\Edit;
use App\Models\Role;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

test('a new role can be update', function () {
    $this->loginAsAdmin();

    $role = Role::query()->create(['name' => 'Test Role']);

    Livewire::test(Edit::class)
        ->call('openEditModal', $role->id)
        ->set('form.name', 'Test Role Updated')
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('roles', [
        'name' => 'Test Role Updated',
    ]);
});

test('a name is required', function () {
    $this->loginAsAdmin();
    $role = Role::query()->create(['name' => 'Test Role']);

    Livewire::test(Edit::class)
        ->call('openEditModal', $role->id)
        ->set('form.name', '')
        ->call('update')
        ->assertHasErrors(['form.name']);
});

test('a name is unique', function () {
    $this->loginAsAdmin();

    $role = Role::query()->create(['name' => 'Role To Rename']);

    Livewire::test(Edit::class)
        ->call('openEditModal', $role->id)
        ->set('form.name', 'admin')
        ->call('update')
        ->assertHasErrors(['form.name']);
});
