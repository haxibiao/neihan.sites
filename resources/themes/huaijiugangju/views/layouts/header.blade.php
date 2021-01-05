@php
$categoryName=array('港剧','电影','动漫','综艺','明星');
@endphp

<header class="app-header__top clearfix" id="header-top">
    <div class="container-xl">
        <div class="app-header">
            <div class="app-header__logo"><a class="logo" href="/"><img class="logo-img"
                        src="/picture/logo-white.png" />
                    <span>HJGJ</span></a></div>
            <ul class="app-header__menu type-list">
                <li class="active hide-xs"><a href="/" class="app-tab">首页</a></li>
                @foreach ($categoryName as $name)
                    <li class="hide-xs">
                        {{-- href="/category/{{ $categoryName[$loop->index] }}"
                        --}}
                        <a href="/category/{{ $loop->index }}" data-type-en="{{ $name }}" class="app-tab">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <ul class="app-header__user">
                <li class="search-box dropdown-hover" href="javascript:;">
                    <form id="search" name="search" method="get" action="/movie/search" data-pjax="">
                        <input type="search" id="kw" name="q" class="search-kw" value="" placeholder="请输入影片名..."
                            autocomplete="off">
                        <button class="search-btn" id="searchbutton" type="submit" name="submit">
                            <i class="iconfont icon-search"></i>
                        </button>
                    </form>
                </li>
                {{-- <li class="hide-xs">
                    <a href="javascript:;" title="留言反馈（暂未开放）" onclick="alert('敬请期待')">
                        <i class="iconfont icon-comments-fill"></i>
                    </a>
                </li>
                <li class="history dropdown-hover hide-xs" title="播放记录">
                    <a href="javascript:;" title="播放记录" onclick="alert('敬请期待')">
                        <i class="iconfont icon-clock-fill"></i>
                    </a> --}}
                    {{-- 未登录 TODO: 播放记录--}}
                    {{-- <div class="dropdown-box fadeInDown">
                        <div class="item clearfix">
                            <p>
                                <a class="pull-right" href="javascript:;"
                                    onclick="MyTheme.Cookie.Del('history','您确定要清除记录吗？');">
                                    [清空]
                                </a>
                                播放记录
                            </p>
                            <div class="history-list clearfix">
                                <p>
                                    <a class="text-333" href="/vodplay/nf3jbhap9/1-yffdj4h.html" title="异兽觉醒">
                                        <span class="pull-right">HD中字</span>异兽觉醒
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div> --}}
                    {{--
                </li> --}}
                {{-- 未登录 TODO: 点击登录--}}
                <li class="menu-user-login " title="点击登录">
                    <a href="javascript:;" onclick="alert('敬请期待')">
                        <i class="iconfont icon-account-fill"></i>
                    </a>
                </li>
                {{-- 已登录 TODO: 登录后的UI交互 --}}
                {{-- <li class="menu dropdown-hover"" title=" 个人中心">
                    <a href=" javascript:;"><i class="iconfont icon-user"></i></a>
                    <div class="dropdown-box fadeInDown">
                        <ul class="item clearfix">
                            <li><a class="text-333" href="/user/index.html">会员中心</a></li>
                            <li><a class="text-333" href="/user/favs.html">我的收藏</a></li>
                            <li><a class="text-333" href="/user/plays.html">
                                </a></li>
                            <li><a class="text-333 logout" href="/user/logout.html">退出</a></li>
                        </ul>
                    </div>
                </li> --}}
                <li class="menu-btn hide-sm" title="展开更多">
                    <a href="javascript:;">
                        <i class="iconfont icon-category"></i>
                    </a>
                    <div class="category-menu">
                        <ul class="menu-list">
                            <li class="menu-item active col-lg-3 col-xs-4">
                                <a href="/">
                                    首页
                                </a>
                            </li>
                            @foreach ($categoryName as $name)
                                <li class="menu-item col-lg-3 col-xs-4">
                                    <a href="/category/{{ $loop->index }}" data-type-en="meiju">
                                        {{ $name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

@push('foot-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', function onClickOpenMenu(event) {
                console.log('xx', $('.app-header__top .menu-btn')[0].contains(event.target));
                if ($('.app-header__top .menu-btn')[0].contains(event.target)) {
                    $('.category-menu').toggleClass('is-open')
                } else {
                    $('.category-menu').removeClass('is-open')
                }
            })
        });

    </script>
@endpush
