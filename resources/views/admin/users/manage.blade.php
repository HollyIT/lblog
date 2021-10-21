<?php
/**
 * @var \App\Models\User[] | \Illuminate\Support\Collection $users
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
                    Role
                </th>
                @include('admin.partials.sort_header', [
                          'key' => 'created_at',
                          'label' => 'Created at',
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
            @foreach($users as $user)
                <tr>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        {{ $user->name }}
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200"><p
                            class="text-gray-900 whitespace-nowrap">{{ $user->role }}</p></td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <p class="text-gray-900 whitespace-nowrap">
                            {{ $user->created_at->format('d M Y') }}
                        </p>
                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <p class="text-gray-900 whitespace-nowrap">
                            {{ $user->updated_at->format('d M Y') }}
                        </p>

                    </td>
                    <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                        <div class="text-gray-900 whitespace-nowrap flex flex-wrap gap-3">

                                @can('update', $user)
                                    <a class="text-indigo-600 hover:text-indigo-900"
                                       href="{{ route('admin.users.edit', ['user' => $user->id]) }}">Edit</a>
                                @endcan
                                @can('delete', $user)
                                    <form method="post" action="{{ route('admin.users.delete', ['user' => $user->id]) }}">
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
            {{ $users->links() }}
        </div>

    </div>
    @component('admin.partials.fab', [
    'to' => route('admin.users.create')
])
    @endcomponent

@endsection
