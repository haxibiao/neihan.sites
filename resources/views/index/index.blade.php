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
            <div class="essays col-xs-12 col-sm-8">
                {{-- 专题分类 --}}

                @include('parts.list.category_list',['categories'=>$data->categories])
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
                        <span class="board_tit one">
                            新上榜
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/index/weekly" target="_blank">
                        <img src="/images/board02.png"/>
                        <span class="board_tit two">
                            7日热门
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/index/monthly" target="_blank">
                        <img src="/images/board03.png"/>
                        <span class="board_tit three">
                            30日热门
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
                @if(Auth::check())
                <recommend-authors></recommend-authors>
                @endif
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
@include('parts.foot')
@stop
