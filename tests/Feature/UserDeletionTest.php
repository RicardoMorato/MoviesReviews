<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserDeletionTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_user_deletion_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete("/api/users/{$user->id}");

        $response
                ->assertStatus(200)
                ->assertExactJson([
                    'data' => "User Deleted",
                ]);
    }

    public function test_receive_error_if_not_authenticated_when_deleting_user(): void
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->delete("/api/users/{$user->id}");

        $response->assertStatus(401)
        ->assertExactJson([
            'message' => "Unauthenticated.",
        ]);
    }

    public function test_receive_error_if_trying_to_delete_another_user(): void
    {
        $user = User::factory()->count(3)->create();

        $response = $this->actingAs($user[1])
                ->delete("/api/users/{$user[2]->id}");

        $response->assertStatus(403)
        ->assertExactJson([
            'data' => null,
            'error' => "Forbidden operation, you cannot delete another user",
        ]);
    }
}
