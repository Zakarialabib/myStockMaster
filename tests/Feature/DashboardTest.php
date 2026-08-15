<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class DashboardTest.
 */
use PHPUnit\Framework\Attributes\Test;
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unauthenticated_users_cant_access_admin_dashboard()
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    #[Test]
    public function not_authorized_users_cant_access_admin_dashboard()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/admin/dashboard');

        $response->assertForbidden();
    }

    #[Test]
    public function admin_can_access_admin_dashboard()
    {
        $this->loginAsAdmin();

        $this->get('/admin/dashboard')->assertOk();
    }
}
