<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\Tenant;
// Transaction factory may not exist; we will assert index structure without creating transactions
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_transactions_index_returns_json()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/transactions')
            ->assertStatus(200)
            ->assertJsonStructure(['transactions', 'filters']);
    }

    public function test_store_transaction_returns_json()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        $product1 = Product::factory()->create(['tenant_id' => $tenant->id, 'stock' => 10, 'price' => 10000]);
        $product2 = Product::factory()->create(['tenant_id' => $tenant->id, 'stock' => 5, 'price' => 20000]);

        $payload = [
            'payment_amount' => 50000,
            'cart' => [
                ['id' => $product1->id, 'qty' => 2],
                ['id' => $product2->id, 'qty' => 1],
            ],
        ];

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/transactions', $payload)
            ->assertStatus(200)
            ->assertJsonStructure(['transaction', 'message']);
    }
}
