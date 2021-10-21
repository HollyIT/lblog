<?php
/**
 * @var \App\Models\Post $post
 */
?>
@extends('layouts.main')
@section('content')
    <div class="container mx-auto px-6 md:px-4">
        <div class="py-6 md:py-12 lg:w-10/12 md:text-center mx-auto">

            <div class="font-medium text-gray-700 text-center">
                {{ $post->published_at?->format('d F Y') }}
            </div>
            <h1 class="heading text-4xl md:text-6xl font-bold font-sans md:leading-tight">
                {{ $post->title }}
            </h1>
            <div class="text-xl text-gray-600 mt-2">{{ $post->description }}</div>
        </div>
        @if ($post->image)
            <a href="{{ $post->image->url }}">
                <img width="1600" height="900" src="{{ $post->image->formatUrl('page') }}" />
            </a>

        @endif
        <div class="flex flex-col md:flex-row py-6 md:py-12">
            <div class="w-full md:w-3/12 pr-3">
                <div class="flex flex-col hidden md:flex mb-3 md:mb-6">
                    <div class="flex items-center mb-3 last:mb-0">
                        @if ($post->user->avatar)
                            <img height="48" width="48" class="rounded-full border-white border-2"
                                 src="/assets/images/author/sage-kirk.jpg" alt="{{ $post->user->name }}">
                        @else
                            <div class="rounded-full border-white border-2 bg-red-200 font-bold flex items-center"
                                 style="width:48px; height: 48px">
                                <span class="mx-auto">
                              {{ $post->user->initials }}
                                </span>
                            </div>

                        @endif
                        <div>
                            <span class="font-medium text-sm ml-1 block">{{ $post->user->name }}</span>
                        </div>

                    </div>
                    <div class="hidden md:block">
                        @foreach ($post->tags as $tag)
                            <a class="p-1 px-3 mr-1 mb-1 inline-block text-xs font-mono rounded bg-green-200 text-green-800 hover:bg-blue-200 hover:text-blue-800 transition duration-300 ease-in-out"
                               href="{{ route('tag.show', ['tag' => $tag]) }}">{{ $tag->tag }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="w-full md:w-9/12">
                {!! $post->prepared_content !!}
            </div>
        </div>
    </div>

@endsection
