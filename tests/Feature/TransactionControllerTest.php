<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Journal;
use App\Models\TypeAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/transactions');

        $response->assertStatus(200);
    }

    public function test_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(
            '/api/transactions',
            [
                "credit_account_id" => 1,
                "debit_account_id" => 2,
                "amount" => 7500,
                "comment" => "Hello there",
                'reference_id' => 0,
                'contact_id' => 0
            ]
        );

        $response->assertStatus(200);
    }

    public function test_creation_contact()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $contact = Contact::create([
            'name' => 'Test User',
            'type_id' => 1,
        ]);

        TypeAccount::insert([
            [
                'type_id' => 1,
                'account_id' => 1,
            ],
            [
                'type_id' => 1,
                'account_id' => 2,
            ]
        ]);

        $response = $this->post(
            '/api/transactions/contact',
            [
                "credit_account_id" => 1,
                "debit_account_id" => 2,
                "amount" => 7500,
                "comment" => "Hello there",
                'reference_id' => 0,
                'contact_id' => $contact->id
            ]
        );

        $response->assertStatus(200);
    }

    public function test_revert_transaction()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $journal = Journal::create(
            [
                "credit_account_id" => 1,
                "debit_account_id" => 2,
                "amount" => 7500,
                "comment" => "Hello there",
            ]
        );

        $response = $this->put(
            "/api/transactions/revert/{$journal->id}",
            [
                "credit_account_id" => 1,
                "debit_account_id" => 2,
                "amount" => 7500,
                "comment" => "Hello there",
                'contact_id' => 0
            ]
        );
        $response->assertStatus(200);
    }
}
