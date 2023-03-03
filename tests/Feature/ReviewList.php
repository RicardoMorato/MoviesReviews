<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\User;
use App\Models\Review;

class ReviewListTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_successfully_retrieve_all_reviews(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        Review::factory()
                ->count(5)
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/reviews');

        $response->assertStatus(200)
                ->assertJsonCount(5, 'data');
    }
    
    public function test_successfully_retrieve_a_specific_review(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        Review::factory()
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/reviews/1');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'movie' => [
                            'id',
                            'title',
                            'content',
                            'movie_id',
                            'user_id',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                ]);
    }
}
