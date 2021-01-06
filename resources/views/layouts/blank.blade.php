<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/logo/{{ env('APP_DOMAIN')}}.small.png" sizes="60*60">
    <link rel="icon" type="image/png" href="/logo/{{ env('APP_DOMAIN')}}.web.png" sizes="120*120">
    <link rel="apple-touch-icon" href="/logo/{{ env('APP_DOMAIN')}}.touch.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') {{ seo_site_name() }} </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content=" @yield('keywords'), {{ seo_site_name() }} ">
    <meta name="description" content=" @yield('description'), {{ seo_site_name() }} ">

    <!-- Styles -->
    <link href="{{ mix('css/guest.css') }}" rel="stylesheet">
    @if(Auth::check())
        <link href="{{ mix('css/editor.css') }}" rel="stylesheet">
    @endif

    @stack('css')

</head>
<body>
    <div id="app" class="blank">
        @yield('content')
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
                    api_url += '&api_token' + api_token;
                }
                return api_url;
            };
            window.csrf_token = '{{ csrf_token() }}';
    </script>
    @endif
    <script type="text/javascript">
            window.csrf_token = '{{ csrf_token() }}';
    </script>

    @if(in_array(request()->path(), [
            'follow',
            'notification',
            'write'
        ]))
        <script src="{{ mix('js/spa.js') }}"></script>
    @endif
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

    @include('parts.to_up')
	
	<div class="container">
		@include('parts.footer')
	</div>

    {{-- 百度自动推送js 更新模板即可 --}}
	{!! cms_seo_js() !!}
</body>
</html>

