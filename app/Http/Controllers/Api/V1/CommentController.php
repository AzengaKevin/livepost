<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::query()->get();

        return response()->json(['data' => $comments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'post_id' => ['required', 'integer'],
            'body' => ['required', 'string']
        ]);

        /** @var User */
        $currentUser = $request->user();

        $newComment = $currentUser->comments()->create($data);

        return response()->json(['data' => $newComment], 203);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return response()->json(['data' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'post_id' => ['nullable', 'integer'],
            'body' => ['nullable', 'string']
        ]);

        $comment->update([
            'post_id' => $data['post_id'] ?? $comment->post_id,
            'body' => $data['body'] ?? $comment->body,
        ]);

        return response()->json(['data' => $comment->fresh()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->noContent();
    }
}
