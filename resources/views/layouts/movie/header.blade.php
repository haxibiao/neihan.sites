<header class="app-header__top clearfix" id="header-top">
    <div class="container-xl">
        <div class="app-header row">
            <div class="app-header__logo"><a class="logo" href="/movie">{{ seo_site_name() }}</a></div>
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
                        <a href="javascript:;">
                            <i class="iconfont icon-clock-fill"></i>
                        </a>
                        <!-- <div class="dropdown-box play-history">
                            <div class="history-box clearfix">
                                <div class="ht-movie_list">
                                    <div class="video_headline">播放记录</div>
                                    @php
                                    $historyMovies
                                    =Auth::user()->movieHistory()->orderByDesc('updated_at')->take(10)->get();
                                    @endphp
                                    @foreach ($historyMovies as $historyItem)
                                        @include('movie.parts.history_movie_item')
                                    @endforeach
                                </div>
                            </div>
                        </div> -->
                    </li>
                @endif
                @if (Auth::check())
                    {{-- 已登录 TODO: 登录后的UI交互 --}}
                    <li title="个人中心" dropdown-target=".user-center" dropdown-toggle="hover">
                        <a href="/movie/collection?type=like"><i class="iconfont icon-usercenter"></i></a>
                        <!-- <div class="dropdown-box user-center open">
                            <ul class="clearfix">
                                <li><a class="item" href="/movie/collection?type=like">我的喜欢</a></li>
                                <li><a class="item" href="/movie/collection?type=favorite">我的收藏</a></li>
                                <li><a class="item logout">退出登录</a></li>
                            </ul>
                        </div> -->
                    </li>
                @else
                    {{-- 未登录 TODO: 点击登录--}}
                    <li title="点击登录" data-toggle="modal" data-target="#login-modal">
                        <a href="javascript:;">
                            <i class="iconfont icon-account-fill"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    
</header>

<!-- @push('foot-scripts')
    <script type="text/javascript" src="js/movie/header.js"></script>
@endpush -->