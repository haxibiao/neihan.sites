<header class="app-header__top clearfix" id="header-top">
    <div class="container-xl">
        <div class="app-header row">
            <div class="app-header__logo"><a class="logo" href="/movie">内涵电影</a></div>
            <ul class="app-header__menu type-list">
                <li class="active hide-xs"><a href="/movie">首页</a></li>
                <li class="hide-xs"><a href="/movie/riju" data-type-en="riju">日剧</a></li>
                <li class="hide-xs"><a href="/movie/meiju" data-type-en="meiju">美剧</a></li>
                <li class="hide-xs"><a href="/movie/hanju" data-type-en="hanju">韩剧</a></li>
                <li class="hide-xs"><a href="/movie/gangju" data-type-en="gangju">港剧</a></li>
            </ul>
            <ul class="app-header__user">
                <li><a href="#" title="留言反馈"><i class="iconfont icon-comments-fill"></i></a></li>
                <li class="hist dropdown-hover">
                    <a href="javascript:;" title=""><i class="iconfont icon-clock-fill"></i></a>
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
                </li>
                <li class="menu-user-login">
                    <a href="javascript:;" onclick="console.log('utils.login();')">
                        <i class="iconfont icon-account-fill"></i>
                    </a>
                </li>
                {{-- <li class="menu dropdown-hover" style="display: none;">
                    <a href="javascript:;"><i class="iconfont icon-user"></i></a>
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
