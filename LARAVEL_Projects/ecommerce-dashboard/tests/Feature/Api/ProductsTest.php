<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_returns_json()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        Product::factory()->count(3)->create(['tenant_id' => $tenant->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/products')
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_store_product_returns_json()
    {
        $tenant = Tenant::factory()->create();
        $owner = User::factory()->create(['tenant_id' => $tenant->id, 'role' => 'owner']);
        $category = Category::factory()->create(['tenant_id' => $tenant->id]);

        $payload = [
            'name' => 'Test Product',
            'price' => 15000,
            'stock' => 10,
            'category_id' => $category->id,
        ];

        $this->actingAs($owner, 'sanctum')
            ->postJson('/api/products', $payload)
            ->assertStatus(200)
            ->assertJsonStructure(['id', 'name']);
    }

    public function test_show_product_returns_json()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $product = Product::factory()->create(['tenant_id' => $tenant->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/products/' . $product->id)
            ->assertStatus(200)
            ->assertJsonStructure(['id', 'name']);
    }
}
