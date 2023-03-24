<?php

namespace App\Repositories;

use Exception;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PostRepository extends BaseRepository
{
    public function __construct(private PostUserRepository $postUserRepository) {
    }
    
    public function create(array $attributes)
    {
        return DB::transaction(function() use ($attributes){

            /** @var Post */
            $newPost = Post::query()->create($attributes);

            if($newPost && $newPost->user){

                $result = $this->postUserRepository->attachUserToPost($newPost, $newPost->user);

                throw_unless(in_array($newPost->user->id, $result['attached']), Exception::class, "Failed to attach user to own post");
                
            }

            return $newPost;

        });
    }

    public function update(Model $post, array $attributes)
    {
        return DB::transaction(function() use($post, $attributes){

            $result = $post->update([
                'title' => data_get($attributes, 'title', $post->title),
                'body' => data_get($attributes, 'body', $post->body)
            ]);

            throw_if(! $result, Exception::class, "Could not updated the post");

            return $result;
        });
    }

    public function delete(Model $post)
    {
        return DB::transaction(function() use($post){

            $result = $post->delete();

            throw_if(!$result, Exception::class, "Could not create the delete");

            return $result;

        });
    }
}


