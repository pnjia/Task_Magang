<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_user_returns_json()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $staff = User::factory()->create(['role' => 'cashier']);

        $payload = ['name' => 'Updated Staff', 'email' => 'staff-new@example.com'];

        $this->actingAs($owner, 'sanctum')
            ->putJson('/api/users/' . $staff->id, $payload)
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'User berhasil diperbarui.']);
    }

    public function test_update_role_returns_json()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $staff = User::factory()->create(['role' => 'cashier']);

        $this->actingAs($owner, 'sanctum')
            ->putJson('/api/users/' . $staff->id . '/role', ['role' => 'owner'])
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Peran pengguna berhasil diperbarui.']);
    }

    public function test_destroy_user_returns_json()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $staff = User::factory()->create(['role' => 'cashier']);

        $this->actingAs($owner, 'sanctum')
            ->deleteJson('/api/users/' . $staff->id)
            ->assertStatus(200)
            ->assertJson(['message' => 'User berhasil dihapus.']);
    }
}
