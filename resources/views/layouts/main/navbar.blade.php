<nav class="font-sans bg-gray-200 flex flex-col text-center sm:flex-row sm:text-left sm:justify-between py-4 px-6 bg-white shadow sm:items-baseline w-full">
    <div class="mb-2 sm:mb-0 flex gap-3 items-center">
        <h1 class="pr-5">
            <a href="/" class="text-2xl no-underline text-grey-darkest hover:text-blue-dark">{{ config('app.name', 'Laravel') }}</a>
        </h1>
        <a href="{{ route('tag.index') }}">Tags</a>
        @if(auth()->user()?->hasAdminAccess())
            <a href="{{ route('admin.posts.index') }}">Admin</a>
            @if(request()->route()->getName() === 'post.show' && auth()->user()?->can('update', $post))
                <a href="{{ route('admin.posts.edit', ['post' => $post->id]) }}">Edit Post</a>
            @endif
        @endif
    </div>
    <div>
        @guest
            <a href="{{ URL::route('login') }}">Login</a>
        @else
            <form method="post" action="{{ url('/logout') }}">
                @csrf
                <button>Logout</button>
            </form>
        @endguest
    </div>
</nav>
