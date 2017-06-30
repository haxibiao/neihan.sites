<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') {{ config('app.name') }} </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content=" @yield('keywords'), {{ config('app.name') }} ">
    <meta name="description" content=" @yield('description'), {{ config('app.name') }} ">

    <!-- Styles -->
    <link href="{{ mix('css/all.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        @include('parts.header')
        @yield('content')

        <nav class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
            <div class="container">
                <a class="navbar-brand" href="/">懂点医</a>
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="http://dongdianyi-1251052432.cosgz.myqcloud.com/apk/dongdianyi.apk" target="_blank">安卓版本下载</a>
                    </li>
                    <li>
                        <a href="/about-us">关于懂点医</a>
                    </li>
                    <li>
                        <a href="https://haxibiao.com">Copyright 2017 @dongdianyi.com, Powered by haxibiao.com</a>
                    </li>

                </ul>
            </div>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/all.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>    

    @stack('scripts')
</body>
</html>
