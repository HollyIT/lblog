<?php
/**
 * @var \App\Models\Image[] | \Illuminate\Support\Collection $images
 */
?>
@extends('admin.layout')
@section('content')
    <div class="py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <table class="min-w-full leading-normal">
            <thead>
            <tr>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Name
                </th>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Group
                </th>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Formats
                </th>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Usage
                </th>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Created at
                </th>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Updated at
                </th>


                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    &nbsp;
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($images as $image)
                <tr>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <img src="{{ $image->formatUrl('thumb')  }}"/>
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        {{ $image->group }}
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        {{ $image->formats->count() }}
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">

                        @if($image->posts->isEmpty())
                        &mdash;
                        @else
                            <ul class="list-disc">
                                @foreach($image->posts as $post)
                                    <li><a href="{{ route('post.show', ['post' => $post->slug]) }}">{{ $post->title }}</a></li>
                                @endforeach
                            </ul>

                        @endif
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <p class="text-gray-900 whitespace-nowrap">
                            {{ $image->created_at->format('d M Y') }}
                        </p>
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <p class="text-gray-900 whitespace-nowrap">
                            {{ $image->updated_at->format('d M Y') }}
                        </p>

                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <div class="text-gray-900 whitespace-nowrap flex flex-wrap gap-3">
                            @can('update', $image)
                                <a class="text-indigo-600 hover:text-indigo-900"
                                   href="{{ route('admin.images.edit', ['image' => $image->id]) }}">Edit</a>
                            @endcan
                            @can('delete', $image)
                                <form method="post"
                                      action="{{ route('admin.images.delete', ['image' => $image->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button class="text-red-600" onclick="return confirm('Are you sure')">Delete
                                    </button>
                                </form>
                            @endcan
                        </div>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pager-links">
            {{ $images->links() }}
        </div>

    </div>
    @component('admin.partials.fab', [
    'to' => route('admin.images.create')
])
    @endcomponent

@endsection
