<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') - {{ config('app.name') }} </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content=" @yield('keywords'), {{ config('app.name') }} ">
    <meta name="description" content=" @yield('description'),{{ config('app.name') }} ">

    <!-- Styles -->
    <link href="{{ mix('css/all.css') }}" rel="stylesheet">
    {{-- <link href="/css/summernote.css" rel="stylesheet"> --}}

</head>
<body>
    <div id="app">
        @include('parts.header')

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/all.js') }}"></script>
    
    <!-- include summernote css/js-->
    {{-- <script src="/js/summernote.js"></script>
    <script src="/js/summernote-zh-CN.js"></script> --}}

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var editor = $('.editable').summernote({
            lang: 'zh-CN', // default: 'en-US',
            height: 300,
            // toolbar: [
            //     // [groupName, [list of button]]
            //     ['style', ['bold', 'italic', 'underline', 'clear']],
            //     ['font', ['strikethrough', 'superscript', 'subscript']],
            //     ['fontsize', ['fontsize']],
            //     ['color', ['color']],
            //     ['para', ['ul', 'ol', 'paragraph']],
            //     ['height', ['height']]
            //   ]
          });
    </script>
    

    @stack('scripts')
</body>
</html>
