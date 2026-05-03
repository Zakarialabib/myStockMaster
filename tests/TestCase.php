<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Artisan::call('db:seed');
    }

    protected function getAdminRole()
    {
        return Role::find(1);
    }

    protected function getMasterAdmin(): User
    {
        $user = User::query()->where('email', 'admin@gmail.com')->first();

        if ($user === null) {
            throw new RuntimeException('Master admin not found. Ensure DatabaseSeeder runs (SuperUserSeeder).');
        }

        return $user;
    }

    protected function loginAsAdmin($admin = false)
    {
        if (! $admin) {
            $admin = $this->getMasterAdmin();
        }

        $this->actingAs($admin);

        return $admin;
    }

    protected function logout()
    {
        return auth()->logout();
    }
}
