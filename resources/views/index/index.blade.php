@extends('layouts.app')

@section('title')
    爱你城 - 最暖心的游戏社交网站
@stop
@section('content')
<div id="index">
    <div class="container">
        <div class="row">
            {{-- 轮播图 --}}
            @include('parts.poster')
            {{-- 左侧 --}}
            <div class="main col-xs-12 col-sm-8">
                {{-- 专题分类 --}}

                @include('parts.list.category_list',['categories'=>$data->categories])
                <categorys-list></categorys-list>
                <div class="split_line">
                </div>
                {{-- 文章摘要 --}}
                @include('parts.list.article_list_category',['articles'=>$data->articles])


            </div>
            {{-- 右侧 --}}
            <div class="aside col-sm-4">
                <div class="board">
                    <a href="/index/new-list" target="_blank">
                        <img src="/images/board01.png"/>
                        <span class="board_tit hot_new">
                            新上榜
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/index/weekly" target="_blank">
                        <img src="/images/board02.png"/>
                        <span class="board_tit hot_seven">
                            7日热门
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/index/monthly" target="_blank">
                        <img src="/images/board03.png"/>
                        <span class="board_tit hot_thirty">
                            30日热门
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="#" target="_blank">
                        <img src="/images/board05.png"/>
                        <span class="board_tit hot_school">
                            爱你城官方小课堂
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                </div>
                   {{-- 二维码 --}}
                <div class="app">
                    <img src="/images/scan.jpeg"/>
                    <div class="info">
                        <div class="title">
                            下载爱你城手机App
                            <i class="iconfont icon-youbian">
                            </i>
                        </div>
                        <p>
                            随时随地发现和创作内容
                        </p>
                    </div>
                    <div class="hover_code">
                        <img src="/images/scan.jpeg"/>
                    </div>
                </div>

                @if(Auth::check())
                <recommend-authors></recommend-authors>
                @endif
{{--                 <div class="videos">
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
                </div> --}}
            </div>
        </div>
    </div>
</div>
@include('parts.foot')
@stop
