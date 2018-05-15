<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8"/>
        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <link href="/logo/{{ env('APP_DOMAIN')}}.small.jpg" rel="icon" sizes="60*60" type="image/png"/>
        <link href="/logo/{{ env('APP_DOMAIN')}}.web.jpg" rel="icon" sizes="120*120" type="image/png"/>
        <link href="/logo/{{ env('APP_DOMAIN')}}.touch.jpg" rel="apple-touch-icon"/>
        <!-- CSRF Token -->
        <meta content="{{ csrf_token() }}" name="csrf-token"/>
        <title>
            @yield('title')
        </title>
        <meta content="width=device-width,initial-scale=1" name="viewport"/>
        <meta content=" @yield('keywords'), {{ config('app.name') }} " name="keywords"/>
        <meta content=" @yield('description'), {{ config('app.name') }} " name="description"/>
        <link href="{{ mix('css/a2.css') }}" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div id="app">
            @include('v2.parts.head_user')
            @yield('content')
        </div>
        @if(in_array(request()->path(), [
            'v2/follow',
            'v2/notification',
            'v2/setting'
        ]))
        <script src="{{ mix('js/b2.js') }}">
        </script>
        @else
        <script src="{{ mix('js/a2.js') }}">
        </script>
        @endif

        @stack('scripts')
    </body>
</html>