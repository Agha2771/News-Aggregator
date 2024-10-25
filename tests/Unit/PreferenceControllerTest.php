<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use News\Repositories\Preference\PreferenceRepositoryInterface;
use News\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Http\Response;

class PreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected PreferenceRepositoryInterface $preferenceRepository;
    protected ArticleRepositoryInterface $articleRepository;

    public function setUp(): void
    {
        parent::setUp();

        // Mock repositories
        $this->preferenceRepository = Mockery::mock(PreferenceRepositoryInterface::class);
        $this->articleRepository = Mockery::mock(ArticleRepositoryInterface::class);

        // Mocking Authentication
        $user = \News\Models\User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->app->instance(PreferenceRepositoryInterface::class, $this->preferenceRepository);
        $this->app->instance(ArticleRepositoryInterface::class, $this->articleRepository);
    }

    /** @test */
    public function it_fetches_user_preferences()
    {
        // Create a user and authenticate via Sanctum
        $user = \News\Models\User::factory()->create();  // Assuming you have a User factory
        $this->actingAs($user, 'sanctum'); // Authenticate the user using Sanctum
    
        // Mock user preferences
        $mockedPreferences = collect([
            (object)['id' => 1, 'user_id' => $user->id, 'preferred_source' => 'NewsAPI', 'preferred_category' => 'Technology', 'preferred_author' => 'John Doe' , 'created_at' => now() , 'updated_at' => now()],
        ]);
    
        // Mock the PreferenceRepositoryInterface to return the mocked preferences
        $this->preferenceRepository
            ->shouldReceive('getPreferencesByUserId')
            ->with($user->id) // Pass the user's ID to simulate the Auth::id() call
            ->andReturn($mockedPreferences);
    
        // Make the request to the endpoint
        $response = $this->getJson('/api/user/preferences');
    
        // Assert the response status and structure
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure(['data' => [['id','user_id','preferred_source', 'preferred_category', 'preferred_author' , 'created_at' , 'updated_at']]]);
    }
    /** @test */
    public function it_stores_user_preferences()
{
    // Create a user and authenticate via Sanctum
    $user = \News\Models\User::factory()->create();  // Assuming you have a User factory
    $this->actingAs($user, 'sanctum'); // Authenticate the user using Sanctum

    // Prepare the request data
    $requestData = [
        'preferred_source' => 'NewsAPI',
        'preferred_category' => 'Business',
        'preferred_author' => 'Jane Smith',
    ];

    // Mock the response after preferences are stored
    $mockedPreferences = (object) array_merge(['id' => 1 , 'user_id' => $user->id , 'created_at' => now() , 'updated_at' => now()], $requestData); // Add the ID to the response

    // Mock the PreferenceRepositoryInterface method
    $this->preferenceRepository
        ->shouldReceive('createOrUpdatePreferences')
        ->withArgs(function ($userId, $data) use ($user, $requestData) {
            return $userId === $user->id && 
                   strip_tags($data['preferred_source']) === $requestData['preferred_source'] &&
                   strip_tags($data['preferred_category']) === $requestData['preferred_category'] &&
                   strip_tags($data['preferred_author']) === $requestData['preferred_author'];
        })
        ->andReturn($mockedPreferences);

    $response = $this->postJson('/api/user/preferences', $requestData);
    // Assert the response status and structure
    $response->assertStatus(Response::HTTP_OK)
             ->assertJsonFragment(['id' => 1, 'user_id' => $user->id ,'preferred_source' => 'NewsAPI', 'preferred_category' => 'Business']);
}

    /** @test */
    public function it_fetches_personalized_feed_based_on_preferences()
    {
        // Create a user and authenticate via Sanctum
        $user = \News\Models\User::factory()->create();
        $this->actingAs($user, 'sanctum'); // Authenticate the user using Sanctum
    
        // Mock user preferences
        $mockedPreferences = collect([
            (object)[
                'id' => 1,
                'user_id' => $user->id,
                'preferred_source' => 'NewsAPI',
                'preferred_category' => 'Business',
                'preferred_author' => 'Jane Smith',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    
        // Mock personalized articles
        $mockedArticles = collect([
            (object)[
                'title' => 'Tech News',
                'source' => 'NewsAPI',
                'author' => 'Jane Smith',
            ],
        ]);
    
        // Mock the preferences and articles in the repository
        $this->preferenceRepository
            ->shouldReceive('getPreferencesByUserId')
            ->with($user->id) // Simulate Auth::id()
            ->andReturn($mockedPreferences);
    
        $this->articleRepository
            ->shouldReceive('getPersonalizedArticles')
            ->with([
                'preferred_source' => $mockedPreferences->pluck('preferred_source'),
                'preferred_category' => $mockedPreferences->pluck('preferred_category'),
                'preferred_author' => $mockedPreferences->pluck('preferred_author'),
            ])
            ->andReturn($mockedArticles);
    
        // Make the request to the endpoint
        $response = $this->getJson('/api/user/preferences/feed');
    
        // Assert the response status and structure
        // Adjusted to match a 'data' wrapping key if necessary
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure(['data' => [['title', 'source', 'author']]]);
    }
    
}
