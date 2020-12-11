<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->

    <title>@yield('title') {{ seo_site_name() }} - 内涵电影</title>
    <meta name="keywords" content="@yield('keywords')" />
    <meta name="description" content="@yield('description')" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="/picture/logo.png">
    <!-- Icons -->
    <link rel="stylesheet" href="https://at.alicdn.com/t/font_2196966_ku6kbo1v4j.css">
    <!-- Styles -->
    <link href="{{ mix('css/movie/movie.css') }}" rel="stylesheet">
    @stack('head-styles')
    <!-- Scripts -->
    <script type="text/javascript" src="{{ mix('js/movie/movie.js') }}"></script>
    @stack('head-scripts')
</head>

<body>
    @yield('top')
    @include('layouts.movie.header')
    <div id="app">
        @yield('content')
    </div>
    @include('layouts.movie.footer')
    @yield('bottom')
</body>

@stack('foot-scripts')

</html>
