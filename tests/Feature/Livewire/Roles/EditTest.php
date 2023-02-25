<?php

use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\assertDatabaseHas;


test('a new role can be update', function () {
    $this->actingAs($this->user);

    $role = Role::create(['name' => 'Test Role']);

    Livewire::test('admin.roles.edit-role', ['role' => $role->id])
        ->set('name', 'Test Role Updated')
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('roles', [
        'name' => 'Test Role Updated',
    ]);
});

test('a name is required', function () {
    $this->actingAs($this->user);
    $role = Role::create(['name' => 'Test Role']);

    Livewire::test('admin.roles.edit-role', ['role' => $role->id])
        ->set('name', '')
        ->call('update')
        ->assertHasErrors(['name' => 'required']);
});

test('a name is unique', function () {
    $this->actingAs($this->user);
    $role = Role::create(['name' => 'Test Role']);

    Livewire::test('admin.roles.edit-role', ['role' => $role->id])
        ->set('name', 'Super Admin')
        ->call('update')
        ->assertHasErrors(['name' => 'unique']);
});