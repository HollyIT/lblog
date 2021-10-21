<?php
/**
 * @var \App\Models\Tag[] | \Illuminate\Database\Eloquent\Collection $tags
 */
?>
@extends('layouts.main')
@section('content')
    <div class="container mx-auto px-6 md:px-4">
        <ul class="list-disc">
        @foreach ($tags as $tag)
            <li>
                <a href="{{ route('tag.show', ['tag' => $tag->slug]) }}">
                    {{ $tag->tag }} ({{ $tag->posts_count }})
                </a>
            </li>
                @endforeach
        </ul>
        <div class="mt-6">
            {!! $tags->links() !!}
        </div>

    </div>

@endsection
