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

    public function test_passing_invalid_email_format(): void
    {
        $response = $this->post('/api/users', [
            'name' => 'Test User',
            'email' => '@.com',
            'password' => 'strong-password',
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'email' => [
                            'The email field must be a valid email address.',
                        ]
                    ]
                ]);
    }

    public function test_passing_invalid_email_provider(): void
    {
        $response = $this->post('/api/users', [
            'name' => 'Test User',
            'email' => 'mail@test.com',
            'password' => 'strong-password',
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'email' => [
                            'The email field must be a valid email address.',
                        ]
                    ]
                ]);
    }

    public function test_passing_invalid_password(): void
    {
        $response = $this->post('/api/users', [
            'name' => 'Test User',
            'email' => 'mail@gmail.com',
            'password' => '123',
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'password' => [
                            'The password field must be at least 8 characters.',
                        ]
                    ]
                ]);
    }
}
