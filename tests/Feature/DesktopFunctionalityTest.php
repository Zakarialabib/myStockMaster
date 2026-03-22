<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\DesktopErrorHandler;
use App\Services\DesktopShortcutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Exception;

class DesktopFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Set cache driver to array for testing
        config(['cache.default' => 'array']);

        // Create a test user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_detect_desktop_mode()
    {
        // Test with desktop header
        $response = $this->withHeaders(['X-Desktop-App' => 'true'])
            ->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_handle_desktop_shortcuts()
    {
        $shortcutService = new DesktopShortcutService();

        // Test getting shortcuts
        $shortcuts = $shortcutService->getShortcuts();
        $this->assertIsArray($shortcuts);
        $this->assertNotEmpty($shortcuts);

        // Test executing a shortcut
        $result = $shortcutService->executeShortcut('ctrl+d', $this->user->id);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
    }

    /** @test */
    public function it_can_handle_desktop_errors()
    {
        $errorHandler = new DesktopErrorHandler();

        // Test handling a PHP error
        $exception = new Exception('Test error message');
        $result = $errorHandler->handleError($exception);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error_id', $result);
        $this->assertArrayHasKey('notification', $result);
    }

    /** @test */
    public function it_can_access_desktop_routes()
    {
        // Test desktop status route
        $response = $this->withHeaders(['X-Desktop-App' => 'true'])
            ->get('/desktop/status');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'desktop_mode',
                'environment',
                'version',
            ]);
    }

    /** @test */
    public function it_can_execute_shortcuts_via_api()
    {
        $response = $this->withHeaders(['X-Desktop-App' => 'true'])
            ->post('/desktop/shortcuts/execute', [
                'shortcut' => 'ctrl+d',
                'context'  => 'dashboard',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'action',
                'message',
            ]);
    }

    /** @test */
    public function it_can_handle_javascript_errors()
    {
        $errorData = [
            'message'   => 'Test JavaScript error',
            'source'    => 'test.js',
            'line'      => 10,
            'column'    => 5,
            'stack'     => 'Error stack trace',
            'userAgent' => 'Test User Agent',
            'url'       => 'http://localhost/test',
        ];

        $response = $this->withHeaders(['X-Desktop-App' => 'true'])
            ->post('/desktop/errors/js', $errorData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'error_id',
                'message',
            ]);
    }

    /** @test */
    public function it_can_get_desktop_shortcuts()
    {
        $response = $this->withHeaders(['X-Desktop-App' => 'true'])
            ->get('/desktop/shortcuts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'shortcuts' => [
                    '*' => [
                        'key',
                        'description',
                        'action',
                        'category',
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_can_handle_desktop_actions()
    {
        $actionData = [
            'action' => 'show_notification',
            'data'   => [
                'title'   => 'Test Notification',
                'message' => 'This is a test notification',
            ],
        ];

        $response = $this->withHeaders(['X-Desktop-App' => 'true'])
            ->post('/desktop/actions', $actionData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ]);
    }

    /** @test */
    public function it_can_access_error_log_page()
    {
        // Test that the error log page is accessible
        $response = $this->withHeaders(['X-Desktop-App' => 'true'])
            ->get('/desktop/errors');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_stores_error_history_for_authenticated_users()
    {
        $errorHandler = new DesktopErrorHandler();
        $exception = new Exception('Test error for history');

        // Handle error while authenticated
        $result = $errorHandler->handleError($exception);

        // Check if error was stored in cache
        $cacheKey = "desktop_error_history_{$this->user->id}";
        $history = Cache::get($cacheKey, []);

        $this->assertNotEmpty($history);
        $this->assertCount(1, $history);
        $this->assertEquals('Test error for history', $history[0]['message']);
    }

    /** @test */
    public function it_handles_errors_gracefully_for_unauthenticated_users()
    {
        Auth::logout();

        $errorHandler = new DesktopErrorHandler();
        $exception = new Exception('Test error without auth');

        // Should not throw an exception even without authenticated user
        $result = $errorHandler->handleError($exception);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error_id', $result);
    }
}
