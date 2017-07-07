<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/{{ env('APP_DOMAIN')}}.small.jpg" sizes="60*60">
    <link rel="icon" type="image/png" href="/{{ env('APP_DOMAIN')}}.web.jpg" sizes="120*120">
    <link rel="apple-touch-icon" href="/{{ env('APP_DOMAIN')}}.touch.jpg">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! get_seoer_meta() !!}

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
        
        @include('parts.footer')
        
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
    {!! get_seoer_footer() !!}
</body>
</html>
