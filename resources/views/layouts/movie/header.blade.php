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
                <li><a href="#" title="留言反馈"><i class="iconfont icon-comments-fill"></i></a></li>
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
    <div class="app-header__search">
        <div class="container-xl">
            <div class="header__search row">
                <ul class="search-ul">
                    <li class="search-type dropdown-hover"><i class="iconfont icon-category"></i>
                        {{-- <div class="dropdown-box bottom fadeInDown clearfix">
                            <ul class="item nav-list clearfix type-list">
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a class="btn btn-sm btn-block btn-default" href="/">首页</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a data-type-en="dianying" class="btn btn-sm btn-block btn-warm"
                                        href="/vodtype/1.html">电影</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a data-type-en="dianshiju" class="btn btn-sm btn-block btn-default"
                                        href="/vodtype/2.html">电视剧</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a data-type-en="zongyi" class="btn btn-sm btn-block btn-default"
                                        href="/vodtype/3.html">综艺</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a data-type-en="dongman" class="btn btn-sm btn-block btn-default"
                                        href="/vodtype/4.html">动漫</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3" style="display: none;">
                                    <a data-type-en="lunli" class="btn btn-sm btn-block btn-default"
                                        href="/vodtype/36.html">伦理</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3" style="display: none;">
                                    <a data-type-en="fuli" class="btn btn-sm btn-block btn-default"
                                        href="/vodtype/37.html">福利</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a data-type-en="jieshuo" class="btn btn-sm btn-block btn-default"
                                        href="/vodtype/47.html">解说</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a class="btn btn-sm btn-block btn-default" href="/topic.html">专题</a>
                                </li>
                                <li class="col-lg-5 col-md-5 col-sm-5 col-xs-3">
                                    <a class="btn btn-sm btn-block btn-default" href="/actor.html">明星</a>
                                </li>
                            </ul>
                        </div> --}}
                    </li>
                    {{-- <li class="search-select dropdown-hover" href="javascript:;">
                        <span class="text">视频</span>
                        <i class="iconfont icon-caret-down text-666"></i>
                        <div class="dropdown-box bottom fadeInDown">
                            <div class="item">
                                <p class="vod" data-action="/vodsearch.html">视频</p>
                                <p class="actor" data-action="/actor/search.html">明星</p>
                            </div>
                        </div>
                    </li> --}}
                    <li class="search-box dropdown-hover" href="javascript:;">
                        <form id="search" name="search" method="get" action="/movie/search" data-pjax="">
                            <input type="search" id="kw" name="q" class="search-kw" value="" placeholder="隐秘而伟大"
                                autocomplete="off">
                            <button class="search-btn" id="searchbutton" type="submit" name="submit">
                                <i class="iconfont icon-search"></i>
                            </button>
                        </form>
                    </li>
                </ul>
                <div class="search-hot hide-xs pull-right">
                    <span><i class="iconfont icon-zhifeiji"></i></span>
                    <a href="/movie?cid=1">科幻</a>
                    <a href="/movie?cid=1">战争</a>
                    <a href="/movie?cid=1">爱情</a>
                    <a href="/movie?cid=1">动作</a>
                    <a href="/movie?cid=1">恐怖</a>
                    <a href="/movie?cid=1">剧情</a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- @push('foot-scripts')
    <script type="text/javascript" src="js/movie/header.js"></script>
@endpush -->