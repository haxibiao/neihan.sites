<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="baidu-site-verification" content="osDJwdLRMO" />
    <meta name="baidu-site-verification" content="7X84HvIWV4" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="@yield('keywords',config('keywords.keywords'))" />
    <meta name="description" content="@yield('description',config('keywords.description'))" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> 怀旧港剧 - 免费电影在线看 </title>
    <link rel="icon" href="/picture/logo.png">
    <!-- Icons -->
    <link rel="stylesheet" href="http://at.alicdn.com/t/font_2196966_ku6kbo1v4j.css">
    <!-- Styles -->
    @stack('top-head-styles')
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    @stack('head-styles')
    <!-- Scripts -->
    <script type="text/javascript" src="{{ mix('js/base.js') }}"></script>
    @stack('head-scripts')
</head>

<body>
    @yield('top')
    @include('layouts.header')
    <div id="app">
        <div class="bg-header"></div>
        @yield('content')
    </div>
    @include('layouts.footer')
    @include('layouts.js_for_footer')
    @yield('bottom')
</body>

@stack('foot-scripts')

</html>
