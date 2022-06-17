<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccountControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_all()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_creation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            '/api/users',
            [
                'name' => 'Test User',
                'email' => 'test@test.com',
                'password' => '12345678'
            ]
        );

        $response->assertStatus(200);
    }

    public function test_updation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $createdUser = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => '12345678'
        ]);

        $response = $this->put(
            "/api/users/{$createdUser->id}",
            [
                'name' => 'Test User',
                'parent_id' => 0
            ]
        );

        $response->assertStatus(200);
    }

    public function test_deletion()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $createdUser = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => '12345678'
        ]);

        $response = $this->delete(
            "/api/users/{$createdUser->id}",
        );

        $response->assertStatus(200);
    }
}
