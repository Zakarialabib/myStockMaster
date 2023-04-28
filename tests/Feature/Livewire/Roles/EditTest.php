<?php

declare(strict_types=1);

use Livewire\Livewire;
use App\Http\Livewire\Role\Edit;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\assertDatabaseHas;

test('a new role can be update', function () {
    $this->loginAsAdmin();

    $role = Role::create(['name' => 'Test Role']);

    Livewire::test(Edit::class, ['role' => $role->id])
        ->set('role.name', 'Test Role Updated')
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('roles', [
        'name' => 'Test Role Updated',
    ]);
});

test('a name is required', function () {
    $this->loginAsAdmin();
    $role = Role::create(['name' => 'Test Role']);

    Livewire::test(Edit::class, ['role' => $role->id])
        ->set('role.name', '')
        ->call('update')
        ->assertHasErrors(['name' => 'required']);
});

test('a name is unique', function () {
    $this->loginAsAdmin();
    $role = Role::create(['name' => 'Test Role']);

    Livewire::test(Edit::class, ['role' => $role->id])
        ->set('role.name', 'Super Admin')
        ->call('update')
        ->assertHasErrors(['name' => 'unique']);
});
