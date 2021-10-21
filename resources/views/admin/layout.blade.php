<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="theme-dark">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ isset($pageTitle) ? $pageTitle . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') . ' admin' }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="api-token" content="{{ auth()->user()->api_token }}">
    @endauth
    <link href="{{ mix('css/admin/admin.css') }}" rel="stylesheet">

</head>
<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        @include('admin.layout.nav')
        @include('admin.layout.header')
        <div style="margin-top:56px;margin-left:220px;" class="w-full" >
            @include('admin.partials.alerts')
            <div class="container px-6 mx-auto grid py-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
