@php
// $movies = \App\Movie::enable()->take(5)->get();
$Movie=array(
'name'=>'不死者之王2',
'movie_type'=>"动漫",
'movie_id'=>1,
'type_name'=>'港剧',
'cover_url'=>"https://cdn-youku-com.diudie.com/app/image/image-5facd03dea2ea1.10509556.jpg",
'introduction'=>"时为2138年。曾卷起一大风潮的虚拟现实体感型网络游戏《YGGDRASIL》即将迎来停服。 玩家飞鼠在曾经以同伴和荣华自傲的根据地纳萨力克地下大坟墓，独自一人安静等待着那一刻。
但，发生了结束时间已过却没有登出的异常事态。 NPC们以自己的意志行动，不止如此，纳萨力克之外展开了从未见过的异世界。 飞鼠为了寻找过去的同伴， 以公会名安兹·乌尔·恭自称，决定在异世界扬名立万。
他与宣誓绝对忠诚的部下一同，向新的地域进军。 将世界收于掌中的死之支配者，于此再临！！下一同，向新的地域进军。 将世界收于掌中的死之支配者，于此再临！！",
'year'=>'2020',
'first_time'=>'2020-10-24',
'directors'=>['导演1','导演2'],
'stars'=>['日野聪','原由实','上坂堇','加藤英美里','内山夕实','加藤将之','三宅']
);
$episodeList=array_fill(0, 30, 'episode');
$movieList = array_fill(0, 12, 'movieObject');
$recommendMovies=array_fill(0, 10, 'movieObject');
$categoryName=array('港剧','电影','综艺','动漫','明星');
@endphp

@extends('layouts.app')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/detail.css') }}">
@endpush

@section('title', '详情页')
@section('keywords', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')
@section('description', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')

@section('content')
    <div class="container-xl">
        <div class="row video-main">
            <div class="main-left col-lg-9 padding-0">
                @include('parts/movie.movie_info_item')
                {{-- 影片信息 --}}
                <div class="play-list module-style">
                    <div class="play-pannel_head clearfix">
                        <h3 style="float: left" class="title">{{ $Movie['name'] }}剧集</h3>
                        <span style="float: right">免费在线播放</span>
                    </div>
                    <div class="row episode-list">
                        <ul class="episode-list-ul clearfix">
                            @foreach ($episodeList as $episode)
                                <li class="col-lg-2 col-md-2  col-sm-3 col-xs-3 padding-0 episode-list-li">
                                    <a href="/play" target="_blank" title="第{{ $loop->index + 1 }}集">
                                        <label class="text-episode">
                                            第{{ $loop->index + 1 }}集
                                        </label>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- 剧集 --}}
                <div class="intro-pannel module-style">
                    <div class="play-pannel_head">
                        <h3 class="title">剧情介绍</h3>
                    </div>
                    <div class="intro" id="introBox" style="color:#666">&emsp;&emsp;{{ $Movie['introduction'] }}
                    </div>
                </div>
                {{-- 简介 --}}
                <div class="like-pannel module-style">
                    <div class="like-pannel_head">
                        <h3 class="title">猜你喜欢</h3>
                    </div>
                    <div class="movie-list clearfix">
                        <ul class="row" style="margin: -10px -20px -10px 0">
                            @foreach ($movieList as $movie)
                                <li class="col-lg-2 col-md-3  col-sm-4 col-xs-4  padding-0">
                                    @include('parts/movie.movie_item')
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- 猜你喜欢 --}}
                <div class="related-video-pannel module-style ">
                    <div class="related-pannel_head">
                        <h3 class="title">相关视频推荐</h3>
                    </div>
                    <div class="movie-list clearfix">
                        <ul class="row" style="margin: -10px -20px -10px 0">
                            @foreach ($recommendMovies as $movie)
                                <li class="col-lg-2 col-md-3  col-sm-4 col-xs-4  padding-0">
                                    @include('parts/movie.movie_item')
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- 相关视频推荐,大屏不显示 --}}
            </div>
            {{-- 左侧主页面 --}}
            <div class="side-right col-lg-3">
                {{-- <div class="qrcode-module module-style">
                    <div class="text-center" style="background-color: #F8F8F8">
                        <p class="text-info">扫码用手机观看</p>
                        <p class="row justify-content-md-center qrcode">
                            <canvas width="160px" height="160px" style="display: none"></canvas>
                            <img class="img-qrcode" src="/picture/logo.png" alt="">
                        </p>
                        <p class="text-info">分享到朋友圈</p>
                    </div>
                </div> --}}
                {{-- 二维码 --}}
                <div class="recommend-module module-style">
                    <div class="rc-title">
                        相关视频推荐
                    </div>
                    <div class="rc-list row">
                        @foreach ($recommendMovies as $item)
                            <div class="rc-item col-lg-12 col-sm-6 clearfix">
                                <a href="/movie/{{ $Movie['movie_id'] }}" target='_blank' class="cover-wrapper">
                                    <div class="common-lazy-img">
                                        <img src="https://cdn-youku-com.diudie.com/app/image/image-5facd03dea2ea1.10509556.jpg"
                                            alt="">
                                    </div>
                                    <div class="video-mask"></div>
                                    <div class="duration">54:29:05</div>
                                </a>
                                <div class="info-wrapper">
                                    <a href="/movie/{{ $Movie['movie_id'] }}" target='_blank'
                                        class="video-title webkit-ellipsis" title="{{ $Movie['name'] }}">
                                        {{ $Movie['name'] }}
                                    </a>
                                    <div class="video-count">{{ mt_rand(10, 99) }}万播放&nbsp;·&nbsp;{{ mt_rand(10, 99) }}万评论
                                    </div>
                                    <div class="comment-count"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- 相关影视推荐 --}}
            </div>
            {{-- 右侧边栏唯大屏时展示 --}}
        </div>
    </div>

@endsection
