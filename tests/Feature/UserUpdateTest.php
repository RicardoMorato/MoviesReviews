<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_user_update_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put("/api/users/{$user->id}", [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
        ]);

        $response
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'email',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ])
                ->assertJsonFragment([
                    'name' => 'Test User',
                    'email' => 'test@gmail.com'
                ]);
    }

    public function test_receive_error_if_not_authenticated_when_updating_user(): void
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->put("/api/users/{$user->id}", [
                    'name' => 'Test User',
                    'email' => 'test@gmail.com',
                ]);

        $response->assertStatus(401)
        ->assertExactJson([
            'message' => "Unauthenticated.",
        ]);
    }

    public function test_receive_error_if_trying_to_update_another_user(): void
    {
        $user = User::factory()->count(3)->create();

        $response = $this->actingAs($user[1])
                ->put("/api/users/{$user[2]->id}", [
                    'name' => 'Test User',
                    'email' => 'test@gmail.com',
                ]);

        $response->assertStatus(403)
        ->assertExactJson([
            'data' => null,
            'error' => "Forbidden operation, you cannot update another user",
        ]);
    }

    public function test_passing_invalid_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put("/api/users/{$user->id}", [
            'name' => 'R',
            'email' => 'test@gmail.com',
        ]);

        $response
                ->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'name' => [
                            'The name field must be at least 3 characters.',
                        ]
                    ]
                ]);
    }

    public function test_passing_invalid_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put("/api/users/{$user->id}", [
            'name' => 'Test User',
            'email' => 'a@.com',
        ]);

        $response
                ->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'email' => [
                            'The email field must be a valid email address.',
                        ]
                    ]
                ]);
    }
}
