<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $user = User::factory()->create();

        $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => $user->password,
        ]);
        Auth::login($user);
        $this->assertTrue(Auth::check());
    }

    public function test_registration()
    {
        $response = $this->post(
            "/api/auth/register",
            [
                'name' => 'Test User',
                'email' => 'test@test.com',
                'password' => '12345678'
            ]
        );

        $response->assertStatus(200);
    }
}
