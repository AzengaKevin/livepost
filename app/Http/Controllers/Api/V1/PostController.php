<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->get();

        return response()->json([
            'data' => $posts
        ], 200); 
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

        $newPost = Post::query()->create($data);

        return response()->json([
            'data' => $newPost
        ], 203);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'data' => $post
        ], 200);
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

        return response()->json([
            'data' => $post->fresh()
        ]);
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
