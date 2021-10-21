<?php
    /**
     * @var  \App\Models\Post $post
     */
    ?>
@extends('admin.layout')
@section('title')
    @if($post->exists)
        Edit Post <span class="font-normal text-base" >(<a href="{{ route('post.show', ['post' => $post->slug]) }}" class="italic underline" >view post</a>)</span>
    @else
        Create Post
    @endif

@endsection
@section('content')
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form method="post"
              enctype="multipart/form-data"
              action="{{ $post->exists ? route('admin.posts.update', ['post' => $post]) : route('admin.posts.store') }}">
            @csrf
            <div class="block text-sm mb-5">
                @include('admin.inputs.text', [
                    'type' => 'text',
                    'label' => 'Title',
                    'name' => 'title',
                    'placeholder' => 'Enter your post title',
                    'value' => $post->title
                ])

            </div>
            <div class="block text-sm mb-5">
                @include('admin.inputs.textarea', [
                  'type' => 'text',
                  'label' => 'Description',
                  'name' => 'description',
                  'placeholder' => 'A short intro to your post',
                  'value' => $post->description
              ])
            </div>
            <div class="block text-sm mb-5">
                @include('admin.inputs.textarea', [
                  'type' => 'text',
                  'label' => 'Body',
                  'name' => 'body',
                  'rows' => 15,
                  'placeholder' => 'Enter your post title',
                  'value' => $post->body
              ])

                @include('admin.inputs.select', [
                  'type' => 'text',
                  'label' => 'Format',
                  'name' => 'body_format',
                  'options' => $bodyFormats,
                  'value' => $post->body_format
                ])
            </div>
            <div class="block text-sm mb-5">
                @include('admin.inputs.text',[
                  'type' => 'text',
                  'label' => 'Tags',
                  'name' => 'tags',
                  'placeholder' => 'Enter comma separated tags',
                  'value' => implode(', ', $post->tags->map(fn($tag) => $tag->tag)->toArray())
                ])
            </div>

            @can('assignOwner', $post)
            <div class="block text-sm mb-5">
                @include('admin.inputs.select', [
                  'label' => 'Author',
                  'name' => 'user',
                  'options' =>$authors,
                  'value' => $post->user_id ?? \Illuminate\Support\Facades\Auth::id()
                ])
            </div>
            @endcan

            <div class="block text-sm mb-5">
                @include('admin.inputs.image', [
                  'label' => 'Image',
                  'name' => 'image',
                  'value' => $post->image?->formatUrl('thumb')
                ])
            </div>
            <div class="block text-sm mb-5">
                @include('admin.inputs.label', ['label' => 'Publish', 'name' => 'publish'])
                <div class="flex-col gap-2 border-2 border-solid border-gray-200 p-4">
                    <label class="inline-flex items-center" for="publish-now">
                        <input id="publish-now" type="checkbox" name="is_published" @if($post->published_at) checked @endif />
                        <span class="ml-2">Publish</span>
                    </label>
                    <div>
                        @include('admin.inputs.text',[
                           'type' => 'datetime-local',
                           'label' => 'Published date',
                           'name' => 'published',
                           'placeholder' => 'Enter comma separated tags',
                           'value' => $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : ''
                         ])

                    </div>
                </div>
            </div>
            <div class="block text-sm mb-5">
                @include('admin.inputs.submit', ['label' => $post->exists ? 'Save' : 'Create'])
            </div>
        </form>
    </div>
@endsection
