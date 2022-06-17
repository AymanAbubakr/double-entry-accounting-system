<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/contacts');

        $response->assertStatus(200);
    }

    public function test_creation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            '/api/contacts',
            [
                'name' => 'Test User',
                'type_id' => 0,
            ]
        );

        $response->assertStatus(200);
    }

    public function test_updation()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = Contact::create([
            'name' => 'Test User',
            'type_id' => 0,
        ]);

        $response = $this->put(
            "/api/contacts/{$contact->id}",
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

        $contact = Contact::create([
            'name' => 'Test User',
            'type_id' => 0,
        ]);

        $response = $this->delete(
            "/api/contacts/{$contact->id}",
        );

        $response->assertStatus(200);
    }
}
