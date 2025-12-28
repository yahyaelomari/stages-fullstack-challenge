<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_last_comment()
    {
        // Create user and article
        $user = User::factory()->create();
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'Content',
            'author_id' => $user->id,
            'published_at' => now(),
        ]);

        // Create exactly one comment
        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'content' => 'Last comment',
        ]);

        // Delete the comment
        $response = $this->deleteJson("/api/comments/{$comment->id}");

        // Should succeed
        $response->assertStatus(200)
            ->assertJson(['message' => 'Comment deleted successfully']);

        // Verify DB is empty
        $this->assertDatabaseCount('comments', 0);
    }
}
