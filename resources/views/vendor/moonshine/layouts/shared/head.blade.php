<meta charset="utf-8" />

<title>@yield('title', config("moonshine.title"))</title>

<meta name="description"
      content="{{ config("moonshine.title") }}"
/>

<meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
/>

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('moonshine::layouts.shared.favicon')

<meta name="msapplication-TileColor" content="{{ moonshineColors()->get('body') }}">
<meta name="theme-color" content="{{ moonshineColors()->get('body') }}">




