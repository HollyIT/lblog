<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="api-token" content="{{ auth()->user()->api_token }}">
    @endauth

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <title>Laravel</title>

</head>
<body>
    @include('layouts.main.navbar')
    <section class="text-gray-600 body-font">
        <div class="container px-5 py-24 mx-auto">

                @yield('content')

        </div>
    </section>
</body>
</html>
