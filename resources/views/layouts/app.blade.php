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

        {!! get_seoer_meta() !!}
        <title>
            @yield('title')
        </title>
        <meta content="width=device-width,initial-scale=1" name="viewport"/>
        <meta content=" @yield('keywords'), {{ config('app.name') }} " name="keywords"/>
        <meta content=" @yield('description'), {{ config('app.name') }} " name="description"/>
        <link href="{{ mix('css/a.css') }}" rel="stylesheet" type="text/css"/>

        @stack('css')
    </head>
    <body>
        <div id="app" {!! is_in_app() ? '' : 'style="padding-top: 60px"' !!}>
            @if(Auth::check())
                @include('parts.head_user')
            @else
                @include('parts.head')
            @endif

            @yield('content')
        </div>

            <!-- Scripts -->
        @if(Auth::check())
        <script type="text/javascript">
                window.tokenize =　 function(api_url){
                    var api_token = '{{ Auth::user()->api_token }}'
                    if(api_url.indexOf('?') === -1) {
                        api_url += '?api_token=' + api_token;
                    } else {
                        api_url += '&api_token=' + api_token;
                    }
                    return api_url;
                };
            window.current_user_id = {{ Auth::user()->id }};
            window.current_user_name = '{{ Auth::user()->name }}';
            window.current_user_avatar = '{{ Auth::user()->avatar }}';
        </script>
        @endif

        @if(in_array(request()->path(), [
            'follow',
            'v1/notification'
        ]))

        <script src="{{ mix('js/b.js') }}">
        </script>
        @else
        <script src="{{ mix('js/a.js') }}">
        </script>
        @endif

         <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        </script>

        {!! get_seoer_footer() !!}

        @stack('scripts')
        @stack('js')
    </body>
</html>