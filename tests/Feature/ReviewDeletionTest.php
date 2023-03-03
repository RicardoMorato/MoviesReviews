<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;
use App\Models\Review;

class ReviewDeletionTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function test_review_deletion_successfully(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->actingAs($user)->delete("/api/reviews/{$review->id}");

        $response
                ->assertStatus(200)
                ->assertExactJson([
                    'data' => 'Review Deleted',
                ]);
    }

    public function test_receive_error_if_not_authenticated_when_deleting_review(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()
                ->for($user)
                ->for($movie)
                ->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete("/api/reviews/{$review->id}", [
            'title' => 'Fake title',
            'content' => 'Fake content',
        ]);

        $response->assertStatus(401)
        ->assertExactJson([
            'message' => "Unauthenticated.",
        ]);
    }

    public function test_receive_error_if_trying_to_delete_a_review_from_another_user(): void
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
                ->delete("/api/reviews/{$review_2->id}", [
                    'title' => 'Fake title',
                    'content' => 'Fake content',
                ]);

        $response->assertStatus(403)
            ->assertExactJson([
                'data' => null,
                'error' => "Forbidden operation, you cannot delete a review from another user",
            ]);
    }
}
