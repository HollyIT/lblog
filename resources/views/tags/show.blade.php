<?php
/**
 * @var \App\Models\Tag $tag
 * @var \App\Models\Post | \Illuminate\Database\Eloquent\Collection $posts
 */
?>
@extends('layouts.main')
@section('content')
    <h2 class="font-bold text-lg mb-4">Tag: {{ $tag->tag }}</h2>
    <div class="flex flex-wrap m-4">
        @foreach($posts as $post)
            <div class="p-4 md:w-1/3">
                @include('shared.post_card', ['post' => $post])
            </div>
        @endforeach
    </div>
    {!! $posts->links() !!}

@endsection
