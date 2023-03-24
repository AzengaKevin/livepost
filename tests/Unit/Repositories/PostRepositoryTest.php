<?php

namespace Tests\Unit\Repositories;

use Exception;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Database\QueryException;
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

    public function test_create_method_works_as_expected_without_owner(): void
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

    public function test_create_method_works_as_expected_with_owner(): void
    {
        /** @var User */
        $user = User::factory()->create();

        // Arrange
        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(),
            'user_id' => $user->id
        ];

        // Act
        $result = $this->repository->create($payload);

        // Assert
        $this->assertNotNull($result, "The post was not even created");

        $this->assertSame($payload['title'], $result->title, "Created post title is not the same");

        $this->assertSame($payload['body'], $result->body, "Created post body is not the same");

        $this->assertSame($payload['user_id'], $result->user_id, "Created post user_id is not the same");

        $this->assertNotNull($result->user, "The post was created without owner");

        $this->assertTrue(in_array($user->id, $result->users->pluck('id')->all()));

    }

    public function test_create_method_missing_required_fields_throws_exception(): void
    {
        /** @var User */
        $user = User::factory()->create();

        // Arrange
        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(),
            'user_id' => $user->id
        ];
        
        collect(['title'])->each(function($field) use($payload){

            $this->expectException(QueryException::class);

            $this->repository->create([...$payload, $field => null]);

        });

    }

    public function test_create_method_missing_with_owner(): void
    {
        $userId = 1;

        // Arrange
        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(),
            'user_id' => $userId
        ];
        
        $this->expectException(QueryException::class);
        
        $this->repository->create($payload);

    }

    public function test_update_method_works_as_excepted()
    {

        // Arrange
        /** @var Post */
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

    public function test_update_method_works_as_excepted_with_individual_fields()
    {
        /** @var Post */
        $post = Post::factory()->create();

        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs()
        ];

        collect(array_keys($payload))->each(function($field) use($post, $payload){

            $result = $this->repository->update($post, [$field => $payload[$field]]);

            $this->assertTrue($result, "The post field, {$field} was not updated");

            $this->assertEquals($payload[$field], $post->fresh()->$field, "The update {$field} is not the same");

        });
        
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

        $this->expectException(Exception::class);

        $this->repository->delete($post);

    }
}
