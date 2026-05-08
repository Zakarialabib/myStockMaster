<?php

declare(strict_types=1);

use App\Livewire\Role\Create;
use App\Models\Role;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

use Spatie\Permission\Models\Permission;

test('the livewire form can be viewed', function () {
    $this->loginAsAdmin();

    $this->get(route('roles.index'))
        ->assertStatus(200);

    Livewire::test(Create::class)->assertOk();
});

test('a new role can be created', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('form.name', 'test role')
        ->call('store');

    assertDatabaseHas('roles', [
        'name' => 'test role',
    ]);
});

test('a role can have multiple permissions attached', function () {
    $this->loginAsAdmin();

    assertDatabaseMissing('roles', [
        'name' => 'test role',
    ]);

    $permissions = Permission::query()->limit(4)->get();
    $permissionIds = $permissions->pluck('id')->all();

    Livewire::test(Create::class)
        ->set('form.name', 'test role')
        ->set('form.permissions', $permissionIds)
        ->call('store')
        ->assertHasNoErrors();

    assertDatabaseHas('roles', [
        'name' => 'test role',
    ]);

    $role = Role::query()->where('name', 'test role')->first();
    expect($role)->not->toBeNull();

    foreach ($permissions as $permission) {
        expect($role->hasPermissionTo($permission))->toBeTrue();
    }
});
