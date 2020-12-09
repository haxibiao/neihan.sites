<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ seo_small_logo() }}" sizes="60*60">
    <link rel="icon" type="image/png" href="{{ seo_small_logo() }}" sizes="120*120">
    <link rel="apple-touch-icon" href="/logo/{{ get_domain() }}.touch.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! get_seo_meta() !!}

    <title> @yield('title') </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content="@yield('keywords'),{{ seo_site_name() }} ">
    <meta name="description" content="@yield('description') ,{{ seo_site_name() }} ">
    @stack('seo_metatags')
    @stack('seo_og_result')

    <!-- Styles -->
    <link href="{{ mix('css/guest.css') }}" rel="stylesheet">
    @if(Auth::check())
        <link href="{{ mix('css/editor.css') }}" rel="stylesheet">
    @endif

    @stack('css')
{!! get_seo_js(seo_site_name()) !!}
</head>
<body>
    <div id="app" class="black">

        @include('parts.header')

        @yield('content')

        @stack('section')

        @stack('modals')

    </div>

    <!-- Scripts -->
    @if(Auth::check())
    <script type="text/javascript">
            window.appName = '{{ seo_site_name() }}';
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
                avatar: '{{ Auth::user()->avatarUrl }}',
                balance: {{ Auth::user()->balance }}
            }
    </script>
    @endif
    <script type="text/javascript">
            window.csrf_token = '{{ csrf_token() }}';
    </script>
    <script src="{{ mix('js/app.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
    @stack('js')
    <script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>

    <div style="display: none">
    {!! get_seo_push() !!}
    {!! get_seo_tj() !!}
    </div>
	
	<div class="container">
		@include('parts.footer')
	</div>

</body>
</html>
