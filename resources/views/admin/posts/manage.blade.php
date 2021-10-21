<?php
/**
 * @var \App\Models\Post[] | \Illuminate\Support\Collection $posts
 */
?>
@extends('admin.layout')
@section('content')
    <div class="py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="px-4 flex mb-2 gap-3 items-center" method="get">
            <select name="filter[visibility]" id="filter-published"
                    class="appearance-none h-full rounded-l border block appearance-none bg-white border-gray-400 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                >
                <option value="all" @if($filters['status'] === 'all') selected @endif>All</option>
                <option value="published" @if($filters['status'] === 'published') selected @endif>Published</option>
                <option value="unpublished" @if($filters['status'] === 'unpublished') selected @endif>Unpublished
                </option>
            </select>
            <div>
                <label for="include-trash">Include trash</label>
                <input type="checkbox" name="filter[trashed]" id="include-trash" value="with"
                       @if($filters['trashed']) checked @endif/>
            </div>


            @include('admin.inputs.submit', ['secondary' => true, 'label' => 'Filter'])
        </form>
        <table class="min-w-full leading-normal">
            <thead>
            <tr>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Title
                </th>
                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    Author
                </th>
                @include('admin.partials.sort_header', [
                          'key' => 'published_at',
                          'label' => 'Published at',
                          'sorted' => $sorted
                          ])
                @include('admin.partials.sort_header', [
                        'key' => 'updated_at',
                        'label' => 'Updated at',
                        'sorted' => $sorted
                        ])

                <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                    &nbsp;
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <a href="{{ route('post.show', ['post' => $post->slug]) }}"
                           class="text-indigo-600 hover:text-indigo-900">{{ $post->title }}</a>
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200"><p
                            class="text-gray-900 whitespace-nowrap">{{ $post?->user->name }}</p></td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <p class="text-gray-900 whitespace-nowrap">
                            {{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}
                        </p>
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <p class="text-gray-900 whitespace-nowrap">
                            {{ $post->updated_at ? $post->updated_at->format('d M Y') : '-' }}
                        </p>

                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <div class="text-gray-900 whitespace-nowrap flex flex-wrap gap-3">
                            @if($post->deleted_at)
                                @can('forceDelete', $post)
                                    <form method="post"
                                          action="{{ route('admin.posts.force_delete', ['post_id' => $post]) }}">
                                        @csrf
                                        @method('delete')
                                        <button class="text-red-600" onclick="return confirm('Are you sure')">Force
                                            Delete
                                        </button>
                                    </form>
                                @endcan

                                @can('restore', $post)
                                    <form method="post" action="{{ route('admin.posts.restore', ['post_id' => $post->id]) }}">
                                        @csrf
                                        <button class="text-red-600" onclick="return confirm('Are you sure')">Restore
                                        </button>
                                    </form>
                                @endcan
                            @else
                                @can('update', $post)
                                    <a class="text-indigo-600 hover:text-indigo-900"
                                       href="{{ route('admin.posts.edit', ['post' => $post->id]) }}">Edit</a>
                                @endcan
                                @can('delete', $post)
                                    <form method="post" action="{{ route('admin.posts.delete', ['post' => $post->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <button class="text-red-600" onclick="return confirm('Are you sure')">Delete
                                        </button>
                                    </form>
                                @endcan

                            @endif
                        </div>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $posts->links() }}
        </div>

    </div>
    @component('admin.partials.fab', [
    'to' => route('admin.posts.create')
])
    @endcomponent

@endsection
