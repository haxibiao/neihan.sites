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

        <link href="{{ mix('css/a.css') }}" rel="stylesheet" type="text/css"/>

    </head>
    <body>
        <div id="app">
            @include('v1.parts.head')
            @yield('content')
            {{-- @include('v1.parts.foot') --}}
        </div>


        @if(in_array(request()->path(), [
            'v1/follow',
            'v1/notification'
        ]))
            <script src="{{ mix('js/b.js') }}"></script>
        @else
            <script src="{{ mix('js/a.js') }}"></script>
        @endif

        @stack('scripts')
    </body>
</html>