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
        <link href="{{ mix('css/v1.css') }}" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        @yield('content')
        <script src="{{ mix('js/v1.js') }}">
        </script>
        @stack('scripts')
    </body>
</html>