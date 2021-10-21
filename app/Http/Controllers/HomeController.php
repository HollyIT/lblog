<?php

namespace App\Http\Controllers;

use App\Models\Post;

class HomeController extends Controller
{
    public function handle()
    {
        return view('home', [
            'posts' => Post::with('user', 'tags', 'image', 'image.formats')->orderBy('published_at', 'desc')->paginate(12),
        ]);
    }
}
