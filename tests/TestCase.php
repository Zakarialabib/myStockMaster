<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        try {
            Artisan::call('db:seed');
        } catch (Exception $e) {
            // Ignore seeding errors in tests if already seeded
        }
    }

    protected function getAdminRole()
    {
        return Role::find(1);
    }

    protected function getMasterAdmin()
    {
        return User::find(1);
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
