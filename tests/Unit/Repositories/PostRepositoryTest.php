<?php

namespace Tests\Unit\Repositories;

use App\Exceptions\Api\GeneralApiException;
use Tests\TestCase;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ?PostRepository $repository = null;

    public function setUp() : void
    {

        parent::setUp();

        $this->repository = $this->app->make(PostRepository::class);
        
    }

    public function test_create_method_works_as_expected(): void
    {
        // Arrange
        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs()
        ];

        // Act
        $result = $this->repository->create($payload);

        // Assert
        $this->assertNotNull($result, "The post was not even created");

        $this->assertSame($payload['title'], $result->title, "Created post title is not the same");
        $this->assertSame($payload['body'], $result->body, "Created post body is not the same");
    }

    public function test_update_method_works_as_excepted()
    {

        // Arrange
        $post = Post::factory()->create();
        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs()
        ];

        // Act 
        $result = $this->repository->update($post, $payload);

        // Assert
        $this->assertTrue($result, "The post was not updated");

        $this->assertSame($payload['title'], $post->fresh()->title, "The title was not updated");
        $this->assertSame($payload['body'], $post->fresh()->body, "The body was not updated");
        
    }

    public function test_delete_method_works_as_expected()
    {

        // Arrange
        $post = Post::factory()->create();

        // Act
        $result = $this->repository->delete($post);

        // Assert
        $this->assertTrue($result, "The post was not deleted");

        $this->assertModelMissing($post);
        
    }

    public function test_delete_method_throughs_general_api_exception_incase_it_fails()
    {

        $post = Post::factory()->make();

        $this->expectException(GeneralApiException::class);

        $this->repository->delete($post);

    }
}
