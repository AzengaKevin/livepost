<?php
namespace App\Repositories;

use App\Models\Post;
use App\Models\User;

class PostUserRepository
{
    public function attachUsersToPost(Post $post, array $payload)
    {
        return $post->users()->syncWithoutDetaching($payload);
    }

    public function attachUserToPost(Post $post, User $user)
    {
        return $this->attachUsersToPost($post, [$user->id]);
    }
}
