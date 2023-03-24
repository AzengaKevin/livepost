<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ?PostUserRepository $postUserRepository = null;

    public function setUp() : void
    {
        parent::setUp();

        $this->postUserRepository = $this->app->make(PostUserRepository::class);

    }

    public function testAttachUsersToPostMethodWorksProperlyForAllNewUsers()
    {
        $post = Post::factory()->for(User::factory())->create();

        $users = User::factory()->count(2)->create();

        $result = $this->postUserRepository->attachUsersToPost($post, $usersIds = $users->pluck('id')->all());

        $this->assertEquals($usersIds, $result['attached'], "New post users were not attached");
    }

    public function testAttachUsersToPostMethodWorksProperlyForOldAndNewUsers()
    {
        $post = Post::factory()->for(User::factory())->create();

        $post->users()->attach($user = User::query()->first());

        $users = User::factory()->count(2)->create();

        $this->postUserRepository->attachUsersToPost($post, $usersIds = [...$users->pluck('id')->all(), $user->id]);

        $intersection = array_intersect($usersIds, $newPostUsersIds = $post->fresh()->users->pluck('id')->all());

        $this->assertEquals(count($intersection), count($usersIds), "Not all users were attached to the post");

        $this->assertEquals(count($usersIds), count($newPostUsersIds), "We have more than approriate associations");

    }

    public function testAttachUserToPostMethodWorksProperly()
    {
        $post = Post::factory()->for(User::factory())->create();

        $user = User::query()->first();

        $this->postUserRepository->attachUserToPost($post, $user);

        $this->assertTrue(in_array($user->id, $post->fresh()->users->pluck('id')->all()));
        
    }
}
