<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;
use App\Models\User;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_is_accent_insensitive()
    {
        // Create a user
        $user = User::factory()->create();

        // Create an article with accents
        Article::create([
            'title' => 'Le café du matin',
            'content' => 'Contenu avec café',
            'author_id' => $user->id,
            'published_at' => now(),
        ]);

        // Search without accent
        $response = $this->getJson('/api/articles/search?q=cafe');

        // Should find the article
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Le café du matin']);
    }
}
