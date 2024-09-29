<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('moonshine::layouts.shared.head')
        @vite('resources/css/app.css')
        @moonShineAssets
    </head>
    <body class="antialiased">
        @yield('content')
    </body>
</html>
