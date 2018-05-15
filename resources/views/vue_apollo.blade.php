<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>测试vue + apollo + graphql ...</title>

        <!-- Fonts -->
        <link href="{{ mix('/css/a.css') }}" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div id="app">

            <div class="content">
                <div class="title m-b-md">
                    测试vue + apollo + graphql ...
                </div>

                <apollo></apollo>
            </div>
        </div>

        <script type="text/javascript" src="{{ mix('/js/g.js') }}"></script>
    </body>
</html>
