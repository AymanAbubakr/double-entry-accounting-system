<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAccountCntrollerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_users()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_create_user()
    {

        $user = User::factory()->create();
        $this->actingAs($user);


        $response = $this->post(
            '/api/users',
            [
                'name' => 'Test User',
                'email' => 'test@test.com',
            ]
        );

        $response->assertStatus(302);
    }


    public function test_update_user()
    {
        $user = User::factory()->creatse();
        $this->actingAs($user);

        $response = $this->put(
            '/api/users/1',
            [
                'name' => 'Test User',
                'email' => 'test@test.com',
            ]
        );

        $response->assertStatus(302);
    }

    // public function test_delete_user()
    // {
    //     $user = User::factory()->creatse();
    //     $this->actingAs($user);

    //     $user->delete();

    //     $response = $this->delete(
    //         '/api/users/1',
    //     );

    //     $response->assertStatus(302);
    // }
}
