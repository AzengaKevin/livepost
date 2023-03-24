<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use App\Http\Requests\StoreCommentRequest;

class PostCommentController extends Controller
{
    public function __construct(private CommentRepository $commentRepository)
    {
    }
    public function store(Post $post, StoreCommentRequest $storeCommentRequest)
    {
        $data = $storeCommentRequest->validated();

        /** @var User */
        $currentUser = $storeCommentRequest->user();

        $newComment = $this->commentRepository->create([
            ...$data,
            'post_id' => $post->id,
            'user_id' => $currentUser->id
        ]);

        return new CommentResource($newComment);
    }
}