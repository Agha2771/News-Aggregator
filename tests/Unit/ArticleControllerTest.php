<?php

namespace Tests\Feature;
use Tests\TestCase;
use News\Models\User;
use News\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase; // Refresh the database between tests

    public function test_can_fetch_all_articles()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user); // Simulates the user being authenticated

        // Create some articles using the factory
        Article::factory()->count(5)->create();

        // Call the API endpoint
        $response = $this->getJson('/api/articles'); // Ensure you hit the correct route path
        $response->assertStatus(200)
                 ->assertJsonCount(5 , 'data.records'); // Adjust based on your response structure
    }

    public function test_can_fetch_single_article()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create an article using the factory
        $article = Article::factory()->create();

        // Call the API endpoint for the single article
        $response = $this->getJson('/api/articles/' . $article->id);

        // Assert the response
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $article->title]); // Adjust based on your response structure
    }
}
