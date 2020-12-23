<header class="app-header__top clearfix" id="header-top">
    <div class="container-xl">
        <div class="app-header row">
            <div class="app-header__logo"><a class="logo" href="/">{{ seo_site_name() }}</a></div>
            <ul class="app-header__menu type-list">
                <li class="active hide-xs"><a href="/">首页</a></li>
                <li class="hide-xs"><a href="/movie/riju" data-type-en="riju">日剧</a></li>
                <li class="hide-xs"><a href="/movie/meiju" data-type-en="meiju">美剧</a></li>
                <li class="hide-xs"><a href="/movie/hanju" data-type-en="hanju">韩剧</a></li>
                <li class="hide-xs"><a href="/movie/gangju" data-type-en="gangju">港剧</a></li>
            </ul>
            <ul class="app-header__user">
                <li class="search">
                    <form class="search-form" name="search" method="get" action="/movie/search">
                        <input name="q" type="search" class="search-input"
                            placeholder="{{ isset($queryKeyword) ? $queryKeyword : '搜索想看的' }}">
                        <button class="search-submit" id="searchbutton" type="submit" name="submit">
                            <i class="iconfont icon-search"></i>
                        </button>
                    </form>
                </li>
                {{-- <li><a href="#" title="留言反馈"><i class="iconfont icon-comments-fill"></i></a></li> --}}
                @if (Auth::check())
                    <li title="播放记录" dropdown-target=".play-history" dropdown-toggle="hover">
                        <a href="/movie/favorites?type=history">
                            <i class="iconfont icon-clock-fill"></i>
                        </a>
                    </li>
                @endif
                @if (Auth::check())
                    {{-- 已登录 TODO: 登录后的UI交互 --}}
                    <li title="个人中心" dropdown-target=".user-center" dropdown-toggle="hover">
                        <a href="/movie/favorites?type=like"><i class="iconfont icon-usercenter"></i></a>
                    </li>
                @else
                    {{-- 未登录 TODO: 点击登录--}}
                    <li title="点击登录" data-toggle="modal" data-target="#login-modal">
                        <a href="/login">
                            <i class="iconfont icon-account-fill"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    
</header>