<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserLoginTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_successful_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->get('/api/test-login');

        $response->assertStatus(200);
    }

    public function test_unsuccessful_login(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/test-login');

        $response->assertStatus(401)
                ->assertExactJson([
                    'message' => "Unauthenticated.",
                ]);
    }
}
