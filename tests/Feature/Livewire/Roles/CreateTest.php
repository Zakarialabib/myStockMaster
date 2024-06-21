<?php

declare(strict_types=1);
use App\Livewire\Role\Create;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('the livewire form can be viewed', function () {
    $this->loginAsAdmin();

    $this->get(route('roles.index'))
        ->assertStatus(200);

    // assert livewire component is rendered
    Livewire::test(Create::class);
});

test('a new role can be created', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('role.name', 'test role')
        ->call('create');

    // assert role exists
    assertDatabaseHas('roles', [
        'name' => 'test role',
    ]);
});

test('a role can have multiple permissions attached', function () {
    $this->loginAsAdmin();
    // assert role does not exist
    assertDatabaseMissing('roles', [
        'name' => 'test role',
    ]);

    // create role
    Livewire::test(Create::class)
        ->set('name', 'test role')
        ->set('rolePermissions', ['view users', 'edit users', 'delete users', 'create users'])
        ->call('create')
        ->assertHasNoErrors();

    // assert role exists
    assertDatabaseHas('roles', [
        'name' => 'test role',
    ]);

    // assert role has permissions
    $role = Role::findByName('test role');
    $this->assertTrue($role->hasPermissionTo('view users'));
    $this->assertTrue($role->hasPermissionTo('edit users'));
    $this->assertTrue($role->hasPermissionTo('delete users'));
    $this->assertTrue($role->hasPermissionTo('create users'));
});
