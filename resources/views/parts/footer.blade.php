@if(!is_in_app())
<div class="container top30">
    <ul class="nav navbar-nav">
        {{-- <li class="active">
            <a href="{{ env('APK_URL') }}" target="_blank">安卓版本下载</a>
        </li> --}}
        <li>
            <a href="/about-us">关于{{ config('app.name') }}</a>
        </li>
        <li>
            <a href="https://haxibiao.com" title="This site powered by haxibiao.com">Copyright 2017 @ {{ env('APP_DOMAIN') }}</a>
        </li>
        <li>
            <a href="https://dongdianyi.com">懂点医</a>
        </li>
        <li>
            <a href="https://dongdianyao.com">懂点药</a>
        </li>
        <li>
            <a href="https://dongmeiwei.com">懂美味</a>
        </li>

    </ul>
</div>
@endif