<?php

/** @noinspection ALL */

namespace App\Http\Controllers\Admin;

use App\Actions\SavePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostAdminSaveRequest;
use App\Models\Post;
use App\Models\User;
use App\Queries\PostQuery;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;

class PostsAdminController extends Controller
{
    /**
     * Returns our admin manage posts page.
     *
     * @param  Request  $request
     * @return Factory|View|Application
     */
    public function index(Request $request): Factory|View|Application
    {
        $filters = $request->get('filter');
        $filterValues = [
            'status' => Arr::get($filters, 'visibility', 'all'),
            'trashed' => Arr::get($filters, 'trashed') === 'with',
        ];

        return view('admin.posts.manage', [
            'pageTitle' => 'Manage posts',
            'posts' => PostQuery::start()->with('user')->paginate(10),
            'filters' => $filterValues,
            'sorted' => $request->get('sort', '-published_at'),
        ]);
    }

    /**
     * Stores our new post.
     *
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        return $this->postForm(new Post(), 'Create Post');
    }

    /**
     * Helper to return an actual form for creating or editing a post.
     *
     * @param  Post  $post
     * @param  string  $title
     * @return Factory|View|Application
     */
    protected function postForm(Post $post, string $title): Factory|View|Application
    {
        $post->body_format = $post->body_format ?? config('posts.default_format');

        return view('admin.posts.form', [
            'post' => $post,
            'pageTitle' => $title,
            'authors' => User::whereNotNull('role')->get(['id', 'name'])->mapWithKeys(fn (User $user) => [$user->id => $user->name]),
            'bodyFormats' => collect(config('posts.formats'))->mapWithKeys(fn ($format, $key) => [$key => $format['label']]),
        ]);
    }

    public function store(PostAdminSaveRequest $request): Redirector|Application|RedirectResponse
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return redirect(route('admin.posts.edit', [
            'post' => SavePostAction::from($request)->load(['tags', 'user', 'image']),
        ]))->withSuccess('Post created');
    }

    /**
     * Display our edit form.
     *
     * @param  Post  $post
     * @return Factory|View|Application
     */
    public function edit(Post $post): Factory|View|Application
    {
        return $this->postForm($post, 'Edit Post');
    }

    public function update(PostAdminSaveRequest $request, Post $post): Redirector|Application|RedirectResponse
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return redirect(route('admin.posts.edit', [
            'post' => SavePostAction::from($request, $post)->load(['tags', 'user', 'image']),
        ]))->withSuccess('Post updated');
    }

    public function destroy(Post $post): Redirector|Application|RedirectResponse
    {
        $post->delete();

        return redirect(route('admin.posts.index'))->withSuccess('The post has been deleted');
    }

    /**
     * @param $postId
     * @return Redirector|Application|RedirectResponse
     */
    public function forceDelete($postId): Redirector|Application|RedirectResponse
    {
        $post = Post::withTrashed()->find($postId);
        abort_if(! $post, 404);
        abort_if(! Auth::user()->can('forceDelete', $post), 403);
        $post->forceDelete();

        return redirect(route('admin.posts.index'))->withSuccess('The post has been permanently deleted');
    }

    public function restore($postId)
    {
        $post = Post::withTrashed()->find($postId);

        abort_if(! $post, 404);
        abort_if(! Auth::user()->can('restore', $post), 403);
        $post->restore();

        return redirect(route('admin.posts.index'))->withSuccess('The post has been restored');
    }
}
