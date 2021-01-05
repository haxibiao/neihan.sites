@php
// FIXME: 查看更多暂时是前端写死的跳转到对应分类
$cid = array('0','1','2','3','4','5');
$categoryMovies= ['最新推荐','港剧','电影','动漫','综艺','明星'];
$movieList = array_fill(0, 8, 'movieObject');
$movieTitle = array_fill(0, 18, '我和我的家乡');
$videoList = array_fill(0, 4, 'video_list');
$categoryName=array('港剧','电影','综艺','动漫','明星');
$carousel_data=[
["movie_name"=>"喜宝","cover_url"=>"//liangcang-material.alicdn.com/prod/upload/9725ab61b4354325903b50515e7f1d95.jpg?x-oss-process=image/resize,w_2074/interlace,1/quality,Q_80/sharpen,100"],
["movie_name"=>"相遇别离","cover_url"=>"//liangcang-material.alicdn.com/prod/upload/f653341b35cf42c49a967a1bee6e24eb.jpg?x-oss-process=image/resize,w_2074/interlace,1/quality,Q_80/sharpen,100"],
["movie_name"=>"雌蜂伊甸之子","cover_url"=>"//liangcang-material.alicdn.com/prod/upload/3da0038e5b524d3b8014c2f2144e89f9.jpg?x-oss-process=image/resize,w_2074/interlace,1/quality,Q_80/sharpen,100"],
["movie_name"=>"与晨同光","cover_url"=>"//liangcang-material.alicdn.com/prod/upload/7a4ab8eb374745c0a1151cede5527431.jpg?x-oss-process=image/resize,w_2074/interlace,1/quality,Q_80/sharpen,100"],
];
@endphp

@extends('layouts.app')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/home.css') }}">
@endpush

@section('top')
    <div class="header-top-bg"></div>
@endsection

@section('title', '首页')
@section('keywords', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')
@section('description', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')

@section('content')
    <div class="movies-panel-container">
        <div class="container-xl">
            <div class="row justify-content-md-center">
                <div class="carousel-box">
                    <div style="display: flex,flex-wrap:wrap">
                        <div id="banner" class="col-12 padding-0">
                            <ul id="bg">
                                @foreach ($carousel_data as $item)
                                    <li><a href="/play" title={{ $item['movie_name'] }}>
                                            <img src={{ $item['cover_url'] }} alt={{ $item['movie_name'] }} />
                                    </li></a>
                                @endforeach
                            </ul>
                            <ul id="aList">
                                @foreach ($carousel_data as $item)
                                    <li><img src={{ $item['cover_url'] }} /></li>
                                @endforeach
                            </ul>
                            <div class="left"><i class="iconfont icon-arrow-left icon-arrow"></i></div>
                            <div class="right"><i class="iconfont icon-arrow-right icon-arrow"></i></div>
                        </div>
                    </div>
                </div>
                {{-- 轮播图 --}}
                <div>
                    @foreach ($categoryMovies as $title)
                        <div class="recommend-panel module-style">
                            @if ($title != '明星')
                                <div class="col-lg-9 col-md-9 col-xs-12 movies-panel padding-0">
                                    <div class="panel-head clearfix">
                                        <div class="title">
                                            {{ $title }}
                                        </div>
                                        <a class="more" href="/category/{{ $cid[$loop->index] }}">更多 <i
                                                class="iconfont icon-arrow-right badge"></i></a>
                                    </div>
                                    <div class="movie-list">
                                        <div class="row movie-row">
                                            @foreach ($movieList as $movie)
                                                <div class="col-3  padding-0">
                                                    @include('parts/movie.movie_item')
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    {{-- <ul class="movie-list_title col-row clearfix">
                                        @foreach ($movieTitle as $item)
                                            <li class="col-lg-2 col-md-3 col-xs-6 padding-0 movie-list_box">
                                                <a class="movie-title text-ellipsis" href="/play" title={{ $item }}
                                                    target="_blank">
                                                    {{ $item }}
                                                </a>
                                                <i class="iconfont icon-arrow-right badge"></i>
                                            </li>
                                        @endforeach
                                    </ul> --}}
                                </div>
                                <div class="col-lg-3 col-md-3 hide-xs padding-0 right-list">
                                    @include('parts/movie.video_list_item')
                                </div>
                                {{-- 影视版块 --}}
                            @else
                                <div class="col-12 movies-panel padding-0">
                                    <div class="panel-head clearfix">
                                        <div class="title">
                                            {{ $title }}
                                        </div>
                                        <a class="more" href="/category/{{ $cid[$loop->index] }}">更多 <i
                                                class="iconfont icon-arrow-right badge"></i></a>
                                    </div>
                                    <div class="movie-list">
                                        <div class="row movie-row">
                                            @foreach ($movieList as $movie)
                                                <div class="col-lg-2 col-md-2  col-sm-3 col-xs-3  padding-0">
                                                    @include('parts/movie.star_item')
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                {{-- 明星版块 --}}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="video-list-container">
        <div class="container-xl padding-0">
            <div class="row">
                @foreach ($videoList as $item)
                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        @include('parts/movie.video_list_item')
                    </div>
                @endforeach
            </div>
        </div>
    </div> --}}
@endsection

@push('foot-scripts')
    <script type="text/javascript" src="{{ mix('js/movie/home.js') }}"></script>
@endpush
