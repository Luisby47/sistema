<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('moonshine::layouts.shared.head')
        @moonShineAssets
    </head>
    <body class="antialiased ">
    <x-moonshine::modal_loading/>
    @yield('content')
    </body>
</html>
