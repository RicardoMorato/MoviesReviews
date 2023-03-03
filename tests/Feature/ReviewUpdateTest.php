<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;
use App\Models\Review;

class ReviewUpdateTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_review_update_successfully(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->actingAs($user)->put("/api/reviews/{$review->id}", [
            'title' => 'Fake title',
            'content' => 'Fake content',
        ]);

        $response
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'title',
                        'content',
                        'movie_id',
                        'user_id',
                        'created_at',
                        'updated_at',
                    ],
                ])
                ->assertJsonFragment([
                    'title' => 'Fake title',
                    'content' => 'Fake content'
                ]);
    }

    public function test_receive_error_if_not_authenticated_when_updating_review(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put("/api/reviews/{$review->id}", [
            'title' => 'Fake title',
            'content' => 'Fake content',
        ]);

        $response->assertStatus(401)
        ->assertExactJson([
            'message' => "Unauthenticated.",
        ]);
    }

    public function test_receive_error_if_trying_to_update_a_review_from_another_user(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->count(3)->create();

        $review = Review::factory()
                ->for($user[1])
                ->for($movie)
                ->create();

        $review_2 = Review::factory()
                ->for($user[2])
                ->for($movie)
                ->create();

        $response = $this->actingAs($user[1])
                ->put("/api/reviews/{$review_2->id}", [
                    'title' => 'Fake title',
                    'content' => 'Fake content',
                ]);

        $response->assertStatus(403)
            ->assertExactJson([
                'data' => null,
                'error' => "Forbidden operation, you cannot update a review from another user",
            ]);
    }

    public function test_passing_invalid_title(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->actingAs($user)->put("/api/reviews/{$review->id}", [
            'title' => 'a',
            'content' => 'Fake content',
        ]);

        $response
                ->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'title' => [
                            'The title field must be at least 3 characters.',
                        ]
                    ]
                ]);
    }

    public function test_passing_invalid_content(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->actingAs($user)->put("/api/reviews/{$review->id}", [
            'title' => 'Fake title',
            'content' => 'Fa',
        ]);

        $response
                ->assertStatus(400)
                ->assertExactJson([
                    'data' => null,
                    'error' => [
                        'content' => [
                            'The content field must be at least 3 characters.',
                        ]
                    ]
                ]);
    }
}
