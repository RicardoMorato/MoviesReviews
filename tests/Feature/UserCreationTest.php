<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_user_created_successfully(): void
    {
        $response = $this->post('/api/users', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'strong-password',
        ]);

        $response->assertStatus(201)
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

    public function test_passing_invalid_name(): void
    {
        $response = $this->post('/api/users', [
            'name' => '',
            'email' => 'test@gmail.com',
            'password' => 'strong-password',
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'name' => [
                            'The name field is required.',
                        ]
                    ]
                ]);
    }
}
