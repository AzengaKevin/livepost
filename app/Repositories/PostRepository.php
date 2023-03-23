<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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

            $newPost->users()->attach($newPost->user);

            return $newPost;

        });
    }

    public function update(Model $post, array $attributes)
    {
        DB::transaction(function() use($post, $attributes){

            $post->update([
                'title' => data_get($attributes, 'title', $post->title),
                'body' => data_get($attributes, 'body', $post->body)
            ]);
    
            if($userId = data_get($attributes, 'user_id', null)){

                $post->users()->syncWithoutDetaching($userId);
            }
        });
    }

    public function delete(Model $post)
    {
        DB::transaction(function() use($post){

            $post->delete();

        });
    }
}


