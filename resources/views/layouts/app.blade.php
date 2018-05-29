<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/logo/{{ get_domain() }}.small.png" sizes="60*60">
    <link rel="icon" type="image/png" href="/logo/{{ get_domain() }}.web.png" sizes="120*120">
    <link rel="apple-touch-icon" href="/logo/{{ get_domain() }}.touch.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! get_seo_meta() !!}   

    <title> @yield('title') </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content="@yield('keywords'),{{ config('app.name') }} ">
    <meta name="description" content="@yield('description') ,{{ config('app.name') }} ">

    <!-- Styles -->
    <link href="{{ mix('css/a.css') }}" rel="stylesheet">

    @stack('css')

</head>
<body>
    <div id="app">

        @include('parts.header')

        <div class="container">
        @yield('content')
        </div>

        @stack('section')

        <div id="side-tool">
            <side-tool></side-tool>
            @stack('side_tool')
        </div>

        @stack('modals')
        
    </div>

    <!-- Scripts -->
    @if(Auth::check())
    <script type="text/javascript">
            window.appName = '{{ config('app.name') }}';
            window.tokenize =　 function(api_url){
                var api_token = '{{ Auth::user()->api_token }}'
                if(api_url.indexOf('?') === -1) {
                    api_url += '?api_token=' + api_token;
                } else {
                    api_url += '&api_token=' + api_token;
                }
                return api_url;
            };
            window.user = {
                id: {{ Auth::user()->id }},
                name: '{{ Auth::user()->name }}',
                avatar: '{{ Auth::user()->avatar() }}',
                balance: {{ Auth::user()->balance() }}
            }
    </script>
    @endif
    <script type="text/javascript">
            window.csrf_token = '{{ csrf_token() }}';
    </script> 

    @if(in_array(request()->path(), [
            'follow',
            'notification',
            'settings'
        ]))
        <script src="{{ mix('js/spa.js') }}"></script>
    @else
        @if(Auth::check() && Auth::user()->checkAdmin())
            <script src="{{ mix('js/admin.js') }}"></script>
        @elseif(Auth::check() && Auth::user()->checkEditor())
            <script src="{{ mix('js/editor.js') }}"></script>
        @elseif(Auth::check())
            <script src="{{ mix('js/user.js') }}"></script>
        @else
        <script src="{{ mix('js/guest.js') }}"></script>
        @endif
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

    <div style="display: none">
    {!! get_seo_push() !!}
    {!! get_seo_tj() !!}
    </div>
    
</body>
</html>
