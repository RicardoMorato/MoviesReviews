<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;

class ReviewCreationTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_review_created_successfully(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->actingAs($user)->post('/api/reviews', [
            'title' => 'Fake Review',
            'content' => 'Fake Review content',
            'movie_id' => $movie->id,
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'title',
                        'content',
                        'user_id',
                        'movie_id',
                        'created_at',
                        'updated_at',
                    ],
                ]);
    }

    public function test_receive_an_error_when_not_authenticated(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/reviews', [
            'title' => 'Fake Review',
            'content' => 'Fake Review content',
            'movie_id' => $movie->id,
        ]);

        $response->assertStatus(401)
                ->assertExactJson([
                    'message' => "Unauthenticated.",
                ]);
    }

    public function test_passing_invalid_title(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->actingAs($user)->post('/api/reviews', [
            'title' => '',
            'content' => 'Fake Review content',
            'movie_id' => $movie->id,
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'title' => [
                            'The title field is required.',
                        ]
                    ]
                ]);
    }

    public function test_passing_invalid_content(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $response = $this->actingAs($user)->post('/api/reviews', [
            'title' => 'Fake Review',
            'content' => '',
            'movie_id' => $movie->id,
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'content' => [
                            'The content field is required.',
                        ]
                    ]
                ]);
    }
}
