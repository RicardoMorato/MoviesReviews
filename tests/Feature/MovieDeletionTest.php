<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;

class MovieDeletionTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_successfully_deleting_a_movie(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete("/api/movies/{$movie->id}");

        $response->assertStatus(200)
                ->assertExactJson([
                    'data' => 'Movie Deleted',
                ]);
    }
    
    public function test_receive_an_error_when_passing_a_invalid_movie_id(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete('/api/movies/123');

        $response->assertStatus(404)
                ->assertExactJson([
                    'data' => null,
                    'error' => 'Resource not found'
                ]);
    }
}
