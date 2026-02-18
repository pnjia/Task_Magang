<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_show_returns_json()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/profile')
            ->assertStatus(200)
            ->assertJsonStructure(['user']);
    }

    public function test_profile_update_returns_json()
    {
        $user = User::factory()->create();

        $payload = ['name' => 'New Name', 'email' => 'new@example.com'];

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/profile', $payload)
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Profile updated successfully.']);
    }

    public function test_profile_password_update_returns_json()
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $payload = [
            'current_password' => 'secret',
            'password' => 'newsecret',
            'password_confirmation' => 'newsecret',
        ];

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/profile/password', $payload)
            ->assertStatus(200)
            ->assertJson(['message' => 'Password updated successfully.']);
    }

    public function test_profile_delete_returns_json()
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/profile', ['password' => 'secret'])
            ->assertStatus(200)
            ->assertJson(['message' => 'Account deleted successfully.']);
    }
}
