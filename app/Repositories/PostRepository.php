<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\Api\GeneralApiException;

class PostRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function() use ($attributes){

            /** @var Post */
            $newPost = Post::query()->create([
                'title' => data_get($attributes, 'title', null),
                'body' => data_get($attributes, 'body', null),
                'user_id' => data_get($attributes, 'user_id', null),
            ]);
            
            throw_if(is_null($newPost), GeneralApiException::class, "Could not create the post");

            $newPost->users()->attach($newPost->user);

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

            throw_if(! $result, GeneralApiException::class, "Could not updated the post");
    
            if($userId = data_get($attributes, 'user_id', null)){

                $post->users()->syncWithoutDetaching($userId);
            }

            return $result;
        });
    }

    public function delete(Model $post)
    {
        return DB::transaction(function() use($post){

            $result = $post->delete();

            throw_if(!$result, GeneralApiException::class, "Could not create the post");

            return $result;

        });
    }
}


