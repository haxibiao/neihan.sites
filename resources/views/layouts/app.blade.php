<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/logo/{{ env('APP_DOMAIN')}}.small.jpg" sizes="60*60">
    <link rel="icon" type="image/png" href="/logo/{{ env('APP_DOMAIN')}}.web.jpg" sizes="120*120">
    <link rel="apple-touch-icon" href="/logo/{{ env('APP_DOMAIN')}}.touch.jpg">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! get_seoer_meta() !!}

    <title> @yield('title') {{ config('app.name') }} </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content=" @yield('keywords'), {{ config('app.name') }} ">
    <meta name="description" content=" @yield('description'), {{ config('app.name') }} ">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @stack('css')

</head>
<body>
    <div id="app" {!! is_in_app() ? '' : 'style="padding-top: 60px"' !!}>
        @include('parts.header')

        @yield('content')

        @include('parts.footer')

    </div>

    <!-- Scripts -->
    @if(Auth::check())
    <script type="text/javascript">
            window.tokenize =　 function(api_url){
                var api_token = '{{ Auth::user()->api_token }}'
                if(api_url.indexOf('?') === -1) {
                    api_url += '?api_token=' + api_token;
                } else {
                    api_url += '&api_token' + api_token;
                }
                return api_url;
            };
    </script>
    @endif

    @if(in_array(request()->path(), [
            'follow',
            'notification'
        ]))
        <script src="{{ mix('js/spa.js') }}"></script>
    @else
        <script src="{{ mix('js/app.js') }}"></script>
    @endif


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
    @stack('js')

    {!! get_seoer_footer() !!}

    @include('parts.to_up')
</body>
</html>
