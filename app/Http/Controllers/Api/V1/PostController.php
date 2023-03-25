<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Providers\PaginationServiceProvider;

class PostController extends Controller
{

    public function __construct(private PostRepository $postRepository) {
    }

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
    public function store(StorePostRequest $storePostRequest)
    {
        $data = $storePostRequest->validated();

        /** @var User */
        $currentUser = $storePostRequest->user();

        $newPost = $this->postRepository->create([
            ...$data,
            'user_id' => $currentUser->id
        ]);

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
    public function update(UpdatePostRequest $updatePostRequest, Post $post)
    {
        $data = $updatePostRequest->validated();

        /** @var User */
        $currentUser = $updatePostRequest->user();

        $this->postRepository->update($post, [
            ...$data,
            'user_id' => $currentUser->id
        ]);

        return new PostResource($post->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->postRepository->delete($post);

        return response()->noContent();
        
    }
}
