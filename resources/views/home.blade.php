@extends('layouts.main')
@section('content')
    <div class="flex flex-wrap m-4">
    @foreach($posts as $post)
        <div class="p-4 md:w-1/3">
        @include('shared.post_card', ['post' => $post])
        </div>
    @endforeach
    </div>
    {!! $posts->links() !!}

@endsection
