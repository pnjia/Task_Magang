<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_create_transaction_with_completed_status(): void
    {
        // Setup: Create tenant and staff user
        $tenant = Tenant::create([
            'name' => 'Test Store',
            'slug' => 'test-store',
        ]);

        $user = User::factory()->create([
            'role' => 'staff',
            'tenant_id' => $tenant->id,
        ]);

        // Create products with stock and SMALL prices
        $products = Product::factory(3)->create([
            'tenant_id' => $tenant->id,
            'stock' => 10,
            'price' => 50000, // Rp 50,000 each
            'is_active' => true,
        ]);

        // Act: Login and create transaction
        $this->actingAs($user);

        $payload = [
            'payment_amount' => '500000', // Rp 500,000
            'cart' => [
                ['id' => (string) $products[0]->id, 'qty' => 2],
                ['id' => (string) $products[1]->id, 'qty' => 1],
            ],
        ];

        $response = $this->post('/transactions', $payload);

        // Debug: Check location header
        echo 'Redirect Location: '.($response->headers->get('location') ?? 'none')."\n";
        echo 'Response Status: '.$response->status()."\n";

        // Check for session errors
        if ($response->status() === 302) {
            $sessionErrors = session()->get('errors');
            if ($sessionErrors) {
                echo 'Session Errors: '.json_encode($sessionErrors->all())."\n";
            }
            $sessionMessage = session()->get('error');
            if ($sessionMessage) {
                echo 'Session Error Message: '.$sessionMessage."\n";
            }
        }

        // Should redirect (302)
        $response->assertStatus(302);

        // Check transaction was created with 'completed' status
        $allTransactions = Transaction::count();
        echo "Total transactions in DB: $allTransactions\n";

        $transaction = Transaction::where('status', 'completed')->first();
        if (! $transaction) {
            $allStatuses = Transaction::pluck('status')->unique()->toArray();
            echo 'All status values: '.json_encode($allStatuses)."\n";
        }

        $this->assertNotNull($transaction, 'Transaction with completed status should exist');

        // Assert: Stock was decreased correctly
        $this->assertEquals(8, $products[0]->fresh()->stock, 'Product 0 stock should be 8');
        $this->assertEquals(9, $products[1]->fresh()->stock, 'Product 1 stock should be 9');
        $this->assertEquals(10, $products[2]->fresh()->stock, 'Product 2 stock should remain 10');

        // Assert: Transaction has correct data
        $this->assertEquals('completed', $transaction->status);
        $this->assertTrue($transaction->total_amount > 0);
        $this->assertEquals(500000, $transaction->payment_amount);

        // Assert: Transaction does NOT appear in incoming orders
        $incomingOrders = Transaction::whereIn('status', ['unpaid', 'paid', 'processing', 'shipped'])->count();
        $this->assertEquals(0, $incomingOrders, 'Should have no orders in active status');
    }
}
