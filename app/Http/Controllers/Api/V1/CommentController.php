<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use App\Http\Requests\UpdateCommentRequest;
use App\Providers\PaginationServiceProvider;

class CommentController extends Controller
{

    public function __construct(private CommentRepository $commentRepository) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::query()->paginate(perPage: PaginationServiceProvider::PER_PAGE);

        return CommentResource::collection($comments);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $updateCommentRequest, Comment $comment)
    {
        $data = $updateCommentRequest->validated();

        $this->commentRepository->update($comment, $data);

        return new CommentResource($comment->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {

        $this->commentRepository->delete($comment);

        return response()->noContent();
    }
}
