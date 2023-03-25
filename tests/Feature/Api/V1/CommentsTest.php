<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    public function testCommentsIndexRoute()
    {
        $this->withoutExceptionHandling();

        Comment::factory()->for(User::factory())->for(Post::factory())->count($count = 2)->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.v1.comments.index'));

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                $count,
                fn (AssertableJson $commentJson) =>
                $commentJson->hasAll(["id", "body", "created_at", "updated_at"])
            )->etc()
        );
    }

    public function testCommentsShowRoute()
    {
        $this->withoutExceptionHandling();

        $comment = Comment::factory()->for(User::factory())->for(Post::factory())->create();

        /** @var User */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.v1.comments.show', $comment));

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $commentJson) =>
                $commentJson->hasAll(["id", "body", "created_at", "updated_at"])
            )
        );

    }

    public function testCommentsUpdateRoute()
    {
        $this->withoutExceptionHandling();

        /** @var Comment */
        $comment = Comment::factory()->for(User::factory())->for(Post::factory())->create();

        $payload = ["body" => fake()->paragraph()];

        /** @var User */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson(route('api.v1.comments.update', $comment), $payload);

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $commentJson) =>
                $commentJson->where("body", $payload["body"])->etc()
            )
        );

        $this->assertEquals($payload["body"], $comment->fresh()->body, "The updated comment body do not match");

    }

    public function testCommentsDeleteRoute()
    {
        $this->withoutExceptionHandling();

        /** @var Comment */
        $comment = Comment::factory()->for(User::factory())->for(Post::factory())->create();

        /** @var User */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('api.v1.comments.destroy', $comment));

        $response->assertStatus(204);

        $this->assertModelMissing($comment);

    }
}
