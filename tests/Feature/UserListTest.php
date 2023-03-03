<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserListTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_successfully_retrieve_all_users(): void
    {
        $user = User::factory()->count(3)->create();

        $response = $this->actingAs($user[0])->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/users');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_receive_error_if_not_authenticated_when_listing_all_users(): void
    {
        $user = User::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/users');

        $response->assertStatus(401)
                ->assertExactJson([
                    'message' => "Unauthenticated.",
                ]);
    }

    
    public function test_successfully_retrieve_a_specific_user(): void
    {
        $user = User::factory()->count(3)->create();

        $response = $this->actingAs($user[0])->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/users/1');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'email',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ]);
    }

    public function test_receive_error_if_not_authenticated_when_listing_a_specific_user(): void
    {
        $user = User::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/users/1');

        $response->assertStatus(401)
                ->assertExactJson([
                    'message' => "Unauthenticated.",
                ]);
    }
}
