@if(!is_in_app())
<div class="container border-top top30">
    <ul class="nav navbar-nav">
        {{-- <li class="active">
            <a href="{{ env('APK_URL') }}" target="_blank">安卓版本下载</a>
        </li> --}}
        <li>
            <a href="/about-us">关于{{ config('app.name') }}</a>
        </li>
        <li>
            <a href="https://haxibiao.com">Copyright 2017 @ {{ env('APP_DOMAIN') }}, Powered by haxibiao.com</a>
        </li>

    </ul>
</div>
@endif