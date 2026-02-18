<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_json()
    {
        $payload = [
            'store_name' => 'Test Store',
            'phone' => '081234567890',
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->postJson('/api/register', $payload)
            ->assertStatus(201);
    }

    public function test_login_returns_json()
    {
        $user = User::factory()->create(['email' => 'login@example.com', 'password' => bcrypt('password')]);

        $this->postJson('/api/login', ['email' => 'login@example.com', 'password' => 'password'])
            ->assertStatus(200);
    }
}
