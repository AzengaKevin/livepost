<?php

namespace Tests\Unit\Repositories;

use App\Models\Comment;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ?CommentRepository $commentRepository = null;

    public function setUp() : void
    {
        parent::setUp();

        $this->commentRepository = $this->app->make(CommentRepository::class);
    }
    
    public function test_create_method_works_as_expected()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var Post */
        $post = Post::factory()->create();

        $payload = [
            'body' => fake()->paragraph(),
            'user_id' => $user->id,
            'post_id' => $post->id
        ];

        $result = $this->commentRepository->create($payload);

        $this->assertNotNull($result, "The comment has not been created");

        $this->assertEquals($payload['user_id'], $result->user_id, "The user_id of the created comment is not the same");
        $this->assertEquals($payload['post_id'], $result->post_id, "The post_id of the created comment is not the same");
        $this->assertEquals($payload['body'], $result->body, "The body of the created comment is not the same");
        
    }

    public function test_create_method_throws_exception_with_missing_required_fields()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var Post */
        $post = Post::factory()->create();

        $payload = [
            'body' => fake()->paragraph(),
            'user_id' => $user->id,
            'post_id' => $post->id
        ];

        collect(array_keys($payload))->each(function($field) use($payload){

            $this->expectException(QueryException::class);

            $data = [...$payload, $field => null];

            $this->commentRepository->create($data);
            
        });
        
    }

    public function test_update_methods_works_as_expected()
    {
        /** @var User */
        $comment = Comment::factory()->for(User::factory())->for(Post::factory())->create();

        $payload = ['body' => fake()->paragraph()];

        $result = $this->commentRepository->update($comment, $payload);

        $this->assertTrue($result, "The comment is not been updated");

        $this->assertEquals($payload['body'], $comment->fresh()->body);
        
    }

    public function test_delete_methods_works_as_expected()
    {
        /** @var User */
        $comment = Comment::factory()->for(User::factory())->for(Post::factory())->create();

        $result = $this->commentRepository->delete($comment);

        $this->assertTrue($result, "The comment is not been deleted");

        $this->assertModelMissing($comment);
        
    }
}
