<?php

namespace Tests\Feature;

use App\Models\TypeAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeAccountsControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/typeAccount');

        $response->assertStatus(200);
    }

    public function test_creation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            '/api/typeAccount',
            [
                'type_id' => 1,
                'account_id' => 1,
            ]
        );

        $response->assertStatus(200);
    }

    public function test_updation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $typeAccount = TypeAccount::create([
            'type_id' => 1,
            'account_id' => 1,
        ]);

        $response = $this->put(
            "/api/typeAccount/{$typeAccount->id}",
            [
                'type_id' => 1,
                'account_id' => 1,
            ]
        );

        $response->assertStatus(200);
    }

    public function test_deletion()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $typeAccount = TypeAccount::create([
            'type_id' => 1,
            'account_id' => 1,
        ]);

        $response = $this->delete(
            "/api/typeAccount/{$typeAccount->id}",
        );

        $response->assertStatus(200);
    }
}
