<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Providers\PaginationServiceProvider;

class UserController extends Controller
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->paginate(perPage: PaginationServiceProvider::PER_PAGE);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $storeUserRequest)
    {
        $data = $storeUserRequest->validated();

        $newUser = $this->userRepository->create($data);

        return new UserResource($newUser);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $updateUserRequest, User $user)
    {
        $this->userRepository->update($user, $updateUserRequest->validated());

        return new UserResource($user->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userRepository->delete($user);

        return response()->noContent();
    }
}