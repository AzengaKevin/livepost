<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Providers\PaginationServiceProvider;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->paginate(perPage: PaginationServiceProvider::PER_PAGE);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required'],
        ]);

        /** @var User */
        $currentUser = $request->user();

        $newPost = $currentUser->posts()->create($data);

        return new PostResource($newPost);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable'],
        ]);

        $post->update([
            'title' => $data['title'] ?? $post['title'],
            'body' => $data['body'] ?? $post['body'],
        ]);

        return new PostResource($post->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
        
    }
}
