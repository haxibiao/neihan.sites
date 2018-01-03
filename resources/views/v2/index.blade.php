@extends('v2.layouts.app2')

@section('title')
    爱你城 - 最暖心的游戏社交网站
@stop
@section('content')
<div id="index">
    <div class="container">
        <div class="row">
            {{-- 轮播图 --}}
            @include('v2.parts.poster')
            {{-- 左侧 --}}
            <div class="main col-xs-12 col-sm-8">
                {{-- 专题分类 --}}
                @include('v2.parts.category_list')
                {{-- 分割线 --}}
                <div class="split_line">
                </div>
                {{-- 文章摘要 --}}
                @include('v2.parts.article.article_list_category')
                {{-- 阅读更多文章摘要 --}}
                <a class="load_more" href="javascript:;">
                    阅读更多
                </a>
            </div>
            {{-- 右侧 --}}
            <div class="aside col-sm-4">
                {{-- 热门榜 --}}
                <div class="board">
                    <a href="/v2/recommendations/notes" target="_blank">
                        <img src="/images/board01.png"/>
                        <span class="board_tit hot_new">
                            新上榜
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/v2/trending/weekly" target="_blank">
                        <img src="/images/board02.png"/>
                        <span class="board_tit hot_seven">
                            7日热门
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    <a href="/v2/trending/monthly" target="_blank">
                        <img src="/images/board03.png"/>
                        <span class="board_tit hot_thirty">
                            30日热门
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    {{--
                    <a href="#" target="_blank">
                        <img src="/images/board04.png"/>
                        <span class="board_tit hot_publish">
                            爱你城出版
                            <i class="iconfont icon-youbian">
                            </i>
                        </span>
                    </a>
                    --}}
                    <a href="/v2/category/2" target="_blank">
                        <img src="/images/board05.png"/>
                        <span class="board_tit hot_school">
                            爱你城大学堂
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
                {{-- 推荐作者 --}}
                @include('v2.parts.recommended_authors')
                {{-- 热门视频 --}}
                <div class="topic">
                    <div class="litter_title">
                        <span>
                            热门视频
                        </span>
                        <a href="/v2/category/2" target="_blank">
                            查看更多
                        </a>
                    </div>
                    <a class="hot_video" href="/v2/detail" target="_blank">
                        <img src="/images/details_01.jpeg"/>
                        <div class="note_title">
                            王者荣耀打野必备攻略 5v5野区地图分布详解
                        </div>
                    </a>
                    <a class="hot_video" href="/v2/detail" target="_blank">
                        <img src="/images/details_09.jpg"/>
                        <div class="note_title">
                            王者荣耀最强奶妈蔡文姬怎么玩2.0加强版
                        </div>
                    </a>
                    <a class="hot_video" href="/v2/detail" target="_blank">
                        <img src="/images/details_01.jpeg"/>
                        <div class="note_title">
                            手把手教你玩王者荣耀安琪拉
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('v2.parts.foot')
@stop
