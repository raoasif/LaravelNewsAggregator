<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    // The setUp method runs before each test
    public function setUp(): void
    {
        parent::setUp();

        // Create a user and generate the Bearer token
        $user = User::factory()->create();
        $token = $user->createToken('TestToken'); // This returns a NewAccessToken instance
        $this->token = $token->plainTextToken;  // Access the plain text token
    }

    // Test for retrieving a list of articles
    public function test_it_can_retrieve_a_list_of_articles()
    {
        // Create sample articles
        Article::factory()->count(10)->create();

        // Make a GET request to the articles endpoint with Bearer token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/articles');

        // Assert successful response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'source',
                    'title',
                    'description',
                    'content',
                    'url',
                    'image_url',
                    'category',
                    'author',
                    'published_date',
                ],
            ],
        ]);
    }

    // Test for filtering articles by category
    public function test_it_can_filter_articles_by_category()
    {
        // Create articles with different categories
        Article::factory()->create(['category' => 'Technology']);
        Article::factory()->create(['category' => 'Sports']);

        // Make a GET request with a category filter
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/articles?category=Technology');

        // Assert only articles with the specified category are returned
        $response->assertStatus(200);
        $response->assertJsonFragment(['category' => 'Technology']);
        $response->assertJsonMissing(['category' => 'Sports']);
    }

    // Test for retrieving a single article
    public function test_it_can_retrieve_a_single_article()
    {
        $article = Article::factory()->create();

        // Make a GET request for a single article
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/articles/{$article->id}");

        // Assert successful response
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $article->id]);
    }

    // Test for 404 response when article does not exist
    /* public function test_it_returns_404_for_nonexistent_article()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/articles/9999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Article not found']);
    } */
}
