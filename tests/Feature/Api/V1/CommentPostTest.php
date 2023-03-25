<?php

namespace Tests\Feature\Api\V1;

use App\Models\Comment;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentPostTest extends TestCase
{
    use RefreshDatabase;

    public function testPostCommentStoreRoute()
    {
        /** @var Post */
        $post = Post::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $payload = ['body' => fake()->paragraph()];

        $response = $this->actingAs($user)->postJson(route('api.v1.posts.comments.store', $post), $payload);

        $response->assertStatus(201)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $commentJson) =>
                $commentJson->where("body", $payload["body"])->has("id")->has("created_at")->has("updated_at")
            )
        );

        /** @var Comment */
        $comment = Comment::query()->first();

        $this->assertNotNull($comment, "The comment was not saved in the database");

        $this->assertTrue($comment->user->is($user), "The current user does not own the comment");

        $this->assertTrue($comment->post->is($post), "The comment does not belong to the intended post");
    }
}
