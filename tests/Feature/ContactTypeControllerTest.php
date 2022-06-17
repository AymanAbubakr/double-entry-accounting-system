<?php

namespace Tests\Feature;

use App\Models\ContactType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTypeControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/contactTypes');

        $response->assertStatus(200);
    }

    public function test_creation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            '/api/contactTypes',
            [
                'name' => 'Test',
            ]
        );

        $response->assertStatus(200);
    }

    public function test_updation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $contactType = ContactType::create([
            'name' => 'Test User',
            'type_id' => 0,
        ]);

        $response = $this->put(
            "/api/contactTypes/{$contactType->id}",
            [
                'name' => 'Test',
            ]
        );

        $response->assertStatus(200);
    }

    public function test_deletion()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $contactType = ContactType::create(  [
            'name' => 'Test',
        ]);

        $response = $this->delete(
            "/api/contactTypes/{$contactType->id}",
        );

        $response->assertStatus(200);
    }
}
