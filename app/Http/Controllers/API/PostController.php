<?php

namespace App\Http\Controllers\API;

use App\Actions\SavePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostSaveRequest;
use App\Http\Resources\PostResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Models\Post;
use App\Queries\PostQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return PostResource::collection(PostQuery::start()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostSaveRequest  $request
     * @return PostResource
     */
    public function store(PostSaveRequest $request): PostResource
    {
        return new PostResource(SavePostAction::from($request)->load(['tags', 'user', 'image']));
    }

    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        $post->load(['user', 'image', 'tags']);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostSaveRequest  $request
     * @param  Post  $post
     * @return PostResource
     */
    public function update(PostSaveRequest $request, Post $post): PostResource
    {
        return new PostResource(SavePostAction::from($request, $post)->load(['tags', 'user', 'image']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     * @return ResourceDeletedResponse
     */
    public function destroy(Post $post): ResourceDeletedResponse
    {
        $post->delete();

        return new ResourceDeletedResponse();
    }

    public function restore(Post $post): PostResource
    {
        $post->restore();

        return new PostResource($post);
    }

    public function forceDelete(Post $post): ResourceDeletedResponse
    {
        $post->forceDelete();

        return new ResourceDeletedResponse();
    }
}
