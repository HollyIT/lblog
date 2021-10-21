<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')
            ->whereHas('posts')
            ->orderBy('posts_count', 'desc')
            ->paginate();

        return view('tags.index', [
            'tags' => $tags,
            'pageTitle' => 'Tags',
        ]);
    }

    public function show(Tag $tag)
    {
        $posts = Post::whereHas('tags', function ($query) use ($tag) {
            $query->where('id', $tag->id);
        })->published()->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('tags.show', [
            'tag' => $tag,
            'posts' => $posts,
            'pageTitle' => 'Tag - ' . $tag->tag,
        ]);
    }
}
