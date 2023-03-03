<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;

class MovieUpdateTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_movie_updated_successfully(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->put("/api/movies/{$movie->id}", [
            'name' => 'Test Movie 2',
            'director' => 'Steven Spielberg',
            'genre' => 'Adventure',
            'releaseDate' => '2022-12-01',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'genre',
                        'director',
                        'releaseDate',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ])
                ->assertJsonFragment([
                    'name' => 'Test Movie 2',
                    'director' => 'Steven Spielberg',
                    'genre' => 'Adventure',
                    'releaseDate' => '2022-12-01'
                ]);
    }

    public function test_passing_invalid_name(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->put("/api/movies/{$movie->id}", [
            'name' => '',
            'director' => 'Steven Spielberg',
            'genre' => 'Adventure',
            'releaseDate' => '2022-12-01',
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

    public function test_passing_invalid_director(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->put("/api/movies/{$movie->id}", [
            'name' => 'Test Movie',
            'director' => 'St',
            'genre' => 'Adventure',
            'releaseDate' => '2022-12-01',
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'director' => [
                            'The director field must be at least 3 characters.',
                        ]
                    ]
                ]);
    }

    public function test_passing_invalid_genre(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->put("/api/movies/{$movie->id}", [
            'name' => 'Test Movie',
            'director' => 'Steven Spielberg',
            'genre' => 'Ad',
            'releaseDate' => '2022-12-01',
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'genre' => [
                            'The genre field must be at least 3 characters.',
                        ]
                    ]
                ]);
    }

    public function test_passing_invalid_release_date(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->put("/api/movies/{$movie->id}", [
            'name' => 'Test Movie',
            'director' => 'Steven Spielberg',
            'genre' => 'Adventure',
            'releaseDate' => '01',
        ]);

        $response->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'releaseDate' => [
                            'The release date field must be at least 3 characters.',
                        ]
                    ]
                ]);
    }
}
