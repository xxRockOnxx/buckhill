<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MainTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_blogs(): void
    {
        // Arrange
        Post::factory()->count(50)->create();

        // Act
        $response = $this->get('/api/v1/main/blog?page=2&limit=10&sort=created_at&desc=true');

        // Assert
        $paginator = Post::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', 2);

        $response->assertJson(collect($paginator)->toArray());
    }

    public function test_can_get_blog(): void
    {
        // Arrange
        $post = Post::factory()->create();

        // Act
        $response = $this->get("/api/v1/main/blog/{$post->uuid}");

        // Assert
        $this->assertSuccessResponseMacro($response, $post->toArray());
    }

    public function test_can_not_get_blog(): void
    {
        // Act
        $response = $this->get('/api/v1/main/blog/00000000-0000-0000-0000-000000000000');

        // Assert
        $this->assertErrorResponseMacro($response, 404, 'Post not found');
    }

    public function test_can_get_promtions()
    {
        // Arrange
        Promotion::factory()->count(50)->create();

        // Act
        $response = $this->get('/api/v1/main/promotions?page=2&limit=10&sort=created_at&desc=true');

        // Assert
        $paginator = Promotion::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', 2);

        $response->assertJson(collect($paginator)->toArray());
    }
}
