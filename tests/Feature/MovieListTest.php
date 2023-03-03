<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;

class MovieListTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_successfully_retrieve_all_movies(): void
    {
        $movie = Movie::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/movies');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }
    
    public function test_successfully_retrieve_a_specific_movie(): void
    {
        $movie = Movie::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/movies/1');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'movie' => [
                            'id',
                            'director',
                            'name',
                            'genre',
                            'releaseDate',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                ]);
    }
}
