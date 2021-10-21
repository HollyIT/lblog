<?php
/**
 * @var  \App\Models\Post $image
 */
?>
@extends('admin.layout')
@section('title')
    @if($image->exists)
        Edit Image
    @else
        Create Image
    @endif

@endsection
@section('content')
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form method="post"
              enctype="multipart/form-data"
              action="{{ $image->exists ? route('admin.images.update', ['image' => $image]) : route('admin.images.store') }}">
            @csrf
            @if($image->exists)
            <div class="mb-4">
                <div class="font-bold">Original</div>
                <img class="max-w-full" src="{{ $image->url }}" />
            </div>
            @foreach ($image->formats as $format)
                <div class="mb-4">
                    <div class="font-bold">{{$format->format}}</div>
                    <img class="max-w-full" src="{{ $format->url }}" />
                </div>
                @endforeach

            @endif
            <div class="block text-sm mb-5">
                @include('admin.inputs.text', [
                  'label' => $image->exists ? 'Replace' : 'Image',
                  'type' => 'file',
                  'placeholder' => 'Image',
                  'name' => 'image',
                  'value' => $image->formatUrl('thumb')
                ])
            </div>

            <div class="block text-sm mb-5">
                @include('admin.inputs.submit', ['label' => $image->exists ? 'Save' : 'Create'])
            </div>
        </form>
    </div>

@endsection
