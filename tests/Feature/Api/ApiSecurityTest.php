<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression tests for GitHub issue #284:
 *  - Unauthenticated API access
 *  - SQL injection via whereRaw() / orderBy()
 *  - Mass assignment via $request->all() without validation
 *
 * The project authenticates the API with Laravel Passport (auth:api guard),
 * so protected endpoints must reject unauthenticated callers with 401 and
 * resolve authenticated callers via a Passport token.
 *
 * Note: PHPUnit 12 removed the docblock test annotation, so tests use the
 * PHP attribute form instead.
 */
class ApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function api_resources_require_authentication(): void
    {
        $protected = [
            ['getJson', '/api/products'],
            ['postJson', '/api/products'],
            ['getJson', '/api/users'],
            ['postJson', '/api/users'],
            ['getJson', '/api/customers'],
            ['getJson', '/api/suppliers'],
            ['getJson', '/api/categories'],
            ['getJson', '/api/roles'],
            ['getJson', '/api/warehouses'],
            ['getJson', '/api/expenses'],
            ['getJson', '/api/sync/pull'],
            ['postJson', '/api/sync/push'],
        ];

        foreach ($protected as [$method, $uri]) {
            $response = $this->$method($uri, []);
            $response->assertStatus(401);
        }
    }

    #[Test]
    public function authenticated_request_can_list_products(): void
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
    }

    #[Test]
    public function product_brand_id_filter_is_not_sql_injectable(): void
    {
        Passport::actingAs(User::factory()->create());

        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        Product::factory()->create(['category_id' => $category->id, 'brand_id' => $brand->id]);
        Product::factory()->create(['category_id' => $category->id, 'brand_id' => null]);

        $payload = $brand->id . ' OR 1=1';

        $response = $this->getJson('/api/products?_end=50&brand_id=' . urlencode($payload));
        $response->assertStatus(200);

        $json = $response->json();
        $list = $json['data'] ?? $json;

        // Safe code returns only the single product whose brand_id matches,
        // not all rows (which would be the injection result).
        $this->assertCount(1, $list);
    }

    #[Test]
    public function order_by_column_injection_is_neutralized(): void
    {
        Passport::actingAs(User::factory()->create());
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users?_end=10&_sort=' . urlencode('id; DROP TABLE users'));

        $response->assertStatus(200);
    }

    #[Test]
    public function user_create_ignores_unvalidated_id_field(): void
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/users', [
            'name' => 'Mass Assign',
            'email' => 'massassign@example.com',
            'password' => 'password123',
            'id' => 999999,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'massassign@example.com']);
        $this->assertDatabaseMissing('users', ['id' => 999999]);
    }

    #[Test]
    public function user_create_rejects_invalid_input(): void
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/users', [
            'name' => 'Bad User',
            'email' => 'bad@example.com',
            'password' => '123',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function product_create_rejects_missing_required_fields(): void
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422);
    }
}
