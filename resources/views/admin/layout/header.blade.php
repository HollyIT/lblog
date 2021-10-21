<header class="flex items-center px-6 z-10 bg-white shadow-md dark:bg-gray-800" style="height: 56px;position:fixed;top:0;left:220px;right:0;">
    <div
        class="font-bold text-2xl text-purple-600 dark:text-purple-300"
    >
        @hasSection('title')
            @yield('title')
        @else
            {{ $pageTitle }}
        @endif
    </div>
    <div class="ml-auto flex gap-2">
        {{ \Illuminate\Support\Facades\Auth::user()?->name }}
        <form method="post" action="{{ url('/logout') }}">
            @csrf
            (<button>Logout</button>)
        </form>
    </div>
</header>
