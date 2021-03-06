<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/accounts');

        $response->assertStatus(200);
    }

    public function test_creation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            '/api/accounts',
            [
                'name' => 'Test User',
                'parent_id' => 0,
                'parent_tree_ids' => []
            ]
        );

        $response->assertStatus(200);
    }

    public function test_updation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $account = Account::create([
            'name' => 'For Testing',
            'parent_id' => 0,
            'parent_tree_ids' => []
        ]);

        $response = $this->put(
            "/api/accounts/{$account->id}",
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

        $account = Account::create([
            'name' => 'For Testing',
            'parent_id' => 0,
            'parent_tree_ids' => []
        ]);

        $response = $this->delete(
            "/api/accounts/{$account->id}",
        );

        $response->assertStatus(200);
    }
}
