<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSaveRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Models\User;
use App\Queries\UserQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(UserQuery::start()->paginate(10));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  UserSaveRequest  $request
     * @return UserResource
     */
    public function store(UserSaveRequest $request): UserResource
    {
        return new UserResource($request->persist(new User()));
    }


    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UserSaveRequest  $request
     * @param  User  $user
     * @return UserResource
     */
    public function update(UserSaveRequest $request, User $user): UserResource
    {
        return new UserResource($request->persist($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return ResourceDeletedResponse
     */
    public function destroy(User $user): ResourceDeletedResponse
    {
        $user->delete();

        return new ResourceDeletedResponse();
    }
}
