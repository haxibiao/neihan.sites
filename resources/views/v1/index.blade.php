@extends('v1.layouts.app')

@section('title')
    爱你城 - 最暖心的游戏社交网站
@stop
@section('content')
<div id="index">
    <div class="container">
        <div class="row">
            <div class="carousel col-xs-12">
                <div class="carousel_inner">
                    <div class="item clearfix">
                        <div class="banner">
                            <a href="/v1/detail" target="_blank">
                                <img src="/images/carousel001.jpg"/>
                            </a>
                        </div>
                        <div class="banner">
                            <a href="/v1/detail" target="_blank">
                                <img src="/images/carousel002.jpg"/>
                            </a>
                        </div>
                        <div class="banner">
                            <a href="/v1/detail" target="_blank">
                                <img src="/images/carousel003.jpg"/>
                            </a>
                        </div>
                    </div>
                </div>
                <a class="left carousel_btn" data-slide="prev" href="#indexCarousel" role="button">
                    <i class="iconfont icon-zuobian">
                    </i>
                </a>
                <a class="right carousel_btn" data-slide="next" href="#indexCarousel" role="button">
                    <i class="iconfont icon-youbian">
                    </i>
                </a>
            </div>
            <div class="essays col-xs-12 col-sm-8">
                <div class="classification">
                    <a class="collection" href="/v1/category" target="_blank">
                        <img src="/images/category_01.jpeg"/>
                        <div class="name">
                            王者荣耀
                        </div>
                    </a>
                    <a class="collection" href="/v1/category" target="_blank">
                        <img src="/images/details_04.jpeg"/>
                        <div class="name">
                            绝地求生
                        </div>
                    </a>
                    <a class="collection" href="/v1/category" target="_blank">
                        <img src="/images/category_08.jpg"/>
                        <div class="name">
                            剑侠情缘3
                        </div>
                    </a>
                    <a class="collection" href="/v1/category" target="_blank">
                        <img src="/images/category_03.jpg"/>
                        <div class="name">
                            暗恋
                        </div>
                    </a>
                    <a class="collection" href="/v1/category" target="_blank">
                        <img src="/images/category_04.jpeg"/>
                        <div class="name">
                            恋爱
                        </div>
                    </a>
                    <a class="collection" href="/v1/category" target="_blank">
                        <img src="/images/category_02.jpg"/>
                        <div class="name">
                            热恋
                        </div>
                    </a>
                    <a class="collection" href="/v1/category" target="_blank">
                        <img src="/images/category_02.jpg"/>
                        <div class="name">
                            谈谈情，说说爱
                        </div>
                    </a>
                    <a class="collection more_hot_collection" href="/v1/categories" target="_blank">
                        <div class="name">
                            更多热门专题
                            <i class="iconfont icon-youbian">
                            </i>
                        </div>
                    </a>
                </div>
                <div class="split_line">
                </div>
                @include('v1.parts.index_article_block')
            </div>
            <div class="aside col-sm-4 col-lg-3 col-lg-offset-1">
                <form class="search">
                    <div class="input-group">
                        <input class="form-control" placeholder="搜索" type="text"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="glyphicon glyphicon-search">
                                </i>
                            </button>
                        </span>
                    </div>
                </form>
                <div class="board">
                    <a href="/v1/category" target="_blank">
                        <img src="/images/board01.png"/>
                        <span class="board_tit one">
                            新上榜
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/v1/category" target="_blank">
                        <img src="/images/board02.png"/>
                        <span class="board_tit two">
                            7日热门
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/v1/category" target="_blank">
                        <img src="/images/board03.png"/>
                        <span class="board_tit three">
                            30日热门
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/v1/category" target="_blank">
                        <img src="/images/board04.png"/>
                        <span class="board_tit four">
                            爱你城出版
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/v1/category" target="_blank">
                        <img src="/images/board05.png"/>
                        <span class="board_tit five">
                            爱你城大学堂
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                </div>
                <div class="app">
                    <img src="/images/scan.jpeg"/>
                    <div class="info">
                        <div class="title">
                            下载爱你城手机App
                            <i class="iconfont icon-youbian">
                            </i>
                        </div>
                        <div class="description">
                            随时随地发现和创作内容
                        </div>
                    </div>
                    <div class="hover_code">
                        <img src="/images/scan.jpeg"/>
                    </div>
                </div>
                <div class="daily">
                    <div class="title">
                        <span>
                            爱你城日报
                        </span>
                        <a href="/v1/category" target="_blank">
                            查看往期
                        </a>
                    </div>
                    <a class="note" href="/v1/detail" target="_blank">
                        <img src="/images/details_08.jpg"/>
                        <div class="note_title">
                            恋爱要谈到什么程度，才适合结婚呢？
                        </div>
                    </a>
                    <a class="note" href="/v1/detail" target="_blank">
                        <div class="note_title">
                            三个心理学语言技巧，让你迅速提高情商
                        </div>
                    </a>
                </div>
                <div class="recommended_authors">
                    <div class="title">
                        <span>
                            推荐作者
                        </span>
                        <a href="javascript:;">
                            <i class="glyphicon glyphicon-refresh">
                            </i>
                            换一批
                        </a>
                    </div>
                    <ul class="list">
                        <li>
                            <a class="avatar" href="/v1/user" target="_blank">
                                <img src="/images/photo_02.jpg"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                ＋ 关注
                            </a>
                            <a class="name" href="/v1/user" target="_blank">
                                王佩
                            </a>
                            <p>
                                写了400.9k字 · 13.8k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user" target="_blank">
                                <img src="/images/photo_03.jpg"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                ＋ 关注
                            </a>
                            <a class="name" href="/v1/user" target="_blank">
                                刘淼
                            </a>
                            <p>
                                写了375.5k字 · 20.5k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user" target="_blank">
                                <img src="/images/photo_02.jpg"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                ＋ 关注
                            </a>
                            <a class="name" href="/v1/user" target="_blank">
                                白发老籣
                            </a>
                            <p>
                                写了50.5k字 · 5.7k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user" target="_blank">
                                <img src="/images/photo_03.jpg"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                ＋ 关注
                            </a>
                            <a class="name" href="/v1/user" target="_blank">
                                魏童
                            </a>
                            <p>
                                写了39.4k字 · 1.4k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user" target="_blank">
                                <img src="/images/photo_02.jpg"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                ＋ 关注
                            </a>
                            <a class="name" href="/v1/user" target="_blank">
                                名贵的考拉熊
                            </a>
                            <p>
                                写了104.3k字 · 8.3k喜欢
                            </p>
                        </li>
                    </ul>
                    <a class="find_more" href="/v1/categories" target="_blank">
                        查看全部
                        <i class="iconfont icon-youbian">
                        </i>
                    </a>
                </div>
                <div class="videos">
                    <div class="title">
                        <span>
                            热门视频
                        </span>
                        <a href="/v1/category" target="_blank">
                            查看更多
                        </a>
                    </div>
                    <a class="note" href="/v1/detail" target="_blank">
                        <img src="/images/details_01.jpeg"/>
                        <div class="note_title video_title">
                            王者荣耀打野必备攻略 5v5野区地图分布详解
                        </div>
                    </a>
                    <a class="note" href="/v1/detail" target="_blank">
                        <img src="/images/details_09.jpg"/>
                        <div class="note_title video_title">
                            王者荣耀最强奶妈蔡文姬怎么玩2.0加强版
                        </div>
                    </a>
                    <a class="note" href="/v1/detail" target="_blank">
                        <img src="/images/details_01.jpeg"/>
                        <div class="note_title video_title">
                            手把手教你玩王者荣耀安琪拉
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('v1.parts.foot')
@stop
