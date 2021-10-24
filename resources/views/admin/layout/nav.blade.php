<aside
    style="position: fixed;top:0;left:0;bottom:0;width:220px;"
    class="z-20 hidden w-64 overflow-y-auto bg-gray-800 dark:bg-gray-800 md:block flex-shrink-0"
>
    <div class="container flex items-center bg-indigo-600  shadow-md" style="height: 56px;">
        <a
            class=" ml-6 text-lg font-bold text-white dark:text-gray-200"
            href="/">{{ config('app.name', 'Laravel') }}</a>
    </div>
    <div class=" text-white dark:text-gray-400">
        <ul class="mt-6">
            @component('admin.layout.nav_item', [
            'label' => 'Posts',
            'to' => route('admin.posts.index'),
            'active' => str_starts_with($currentRouteName, 'admin.posts.')
            ])
                @include('admin.icons.post')
            @endcomponent
            @component('admin.layout.nav_item', [
                'label' => 'Images',
                'to' => route('admin.images.index'),
                'active' => str_starts_with($currentRouteName, 'admin.images.')
                ])
                @include('admin.icons.images')
            @endcomponent

            @can('viewAny', \App\Models\User::class)
                @component('admin.layout.nav_item', [
                    'label' => 'Users',
                    'to' => route('admin.users.index'),
                    'active' => str_starts_with($currentRouteName, 'admin.users.')
                    ])
                    @include('admin.icons.users')
                @endcomponent
            @endcan


        </ul>

    </div>
</aside>
