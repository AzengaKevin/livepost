<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function testPostsIndexRoute()
    {
        Post::factory()->count($count = 2)->create();

        $response = $this->getJson(route('api.v1.posts.index'));

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                $count,
                fn (AssertableJson $postAssertableJson) =>
                $postAssertableJson->hasAll(["id", "title", "body", "created_at", "updated_at"])
            )->etc()
        );
    }

    public function testPostsShowRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        /** @var Post */
        $post = Post::factory()->for($user)->create();

        $response = $this->getJson(route('api.v1.posts.show', $post));

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $postAssertableJson) =>
                $postAssertableJson->hasAll(["id", "title", "body", "created_at", "updated_at"])
            )
        );
    }

    public function testPostsStoreRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraph()
        ];

        $response = $this->actingAs($user)->postJson(route('api.v1.posts.store'), $payload);

        $response->assertStatus(201)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $postAssertableJson) =>
                $postAssertableJson->has("id")->where("title", $payload["title"])->where("body", $payload["body"])->etc()
            )
        );

        /** @var Post */
        $post = Post::query()->where("title", $payload["title"])->first();

        $this->assertNotNull($post, "The post has not been created");

        $this->assertEquals($payload["body"], $post->body, "The post body is not the same");

        $this->assertTrue($post->user->is($user), "Current user is not the owenr of the post");
    }

    public function testPostsUpdateRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        /** @var Post */
        $post = Post::factory()->for($user)->create();

        $payload = [
            'title' => fake()->sentence(),
            'body' => fake()->paragraph()
        ];

        $response = $this->actingAs($user)->patchJson(route('api.v1.posts.update', $post), $payload);

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $postAssertableJson) =>
                $postAssertableJson->has("id")->where("title", $payload["title"])->where("body", $payload["body"])->etc()
            )
        );

        $this->assertEquals($payload["title"], $post->fresh()->title, "The updated post title is not the same");
        $this->assertEquals($payload["body"], $post->fresh()->body, "The updated post body is not the same");
    }

    public function testPostsDeleteRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        /** @var Post */
        $post = Post::factory()->for($user)->create();

        $responce = $this->actingAs($user)->deleteJson(route('api.v1.posts.destroy', $post));

        $responce->assertStatus(204);

        $this->assertModelMissing($post);
    }
}
