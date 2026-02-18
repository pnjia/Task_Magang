<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_index_returns_json()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        Category::factory()->count(2)->create(['tenant_id' => $tenant->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/categories')
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_store_category_returns_json()
    {
        $tenant = Tenant::factory()->create();
        $owner = User::factory()->create(['tenant_id' => $tenant->id, 'role' => 'owner']);

        $payload = ['name' => 'New Category'];

        $this->actingAs($owner, 'sanctum')
            ->postJson('/api/categories', $payload)
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Category']);
    }
}
