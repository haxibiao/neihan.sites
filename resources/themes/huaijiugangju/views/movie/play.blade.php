@php
// $movies = \App\Movie::enable()->take(5)->get();
$Movie=array(
'name'=>'顶楼',
'movie_type'=>"剧情",
'movie_id'=>1,
'type_name'=>'韩剧',
'cover_url'=>"https://cdn-youku-com.diudie.com/app/image/image-5facd03dea2ea1.10509556.jpg",
'introduction'=>"该剧讲述为了跻身上流社会而堵上人生、奋力奔走的女主的欲望和母性，以及置业暴富的成功故事。",
'year'=>'2020',
'first_time'=>'2020-10-24',
'directors'=>['朱东民','金顺玉'],
'stars'=>['李智雅',' 柳真',' 严基俊',' 奉太奎 Tae-gyu Bong']
);
$cid = array('2','1','3','4');
$categoryMovies= array_fill(0, 4, '最新电视剧');
$movieList = array_fill(0, 12, 'movieObject');
$movieTitle = array_fill(0, 12, '我和我的家乡');
$videoList = array_fill(0, 4, 'video_list');
$categoryName=array('港剧','电影','综艺','动漫','明星');
$recommendMovies=array_fill(0, 5, 'movieObject');
@endphp

@extends('layouts.app')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/play.css') }}">
@endpush
@push('head-scripts')
    <script src="{{ asset('js/hls.js') }}"></script>
    <script src="{{ asset('js/movie_components.js') }}" defer></script>
@endpush


@section('title', '在线播放')
@section('keywords', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')
@section('description', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')

@section('content')
    <div class="app-player">
        <div class="container-xl">
            {{-- @php
            $first = $movie->series()->oldest()->first();
            @endphp --}}
            {{--
            TODO: 后端传递只一个movie对象，包含影视的详细信息
            --}}

            <movie-player title={{ $Movie['name'] }} movie_id={{ $Movie['movie_id'] }} type={{ $Movie['type_name'] }}
                reigon="未知" current-series={{ 0 }} />
        </div>
    </div>
    <div class="container-xl">
        <div class="row video-main">
            <div class="main-left col-lg-9 padding-0">
                @include('parts/movie.movie_info_item')
                <div class="video-comment">
                    <comment-module />
                </div>
            </div>
            <div class="side-right col-lg-3">
                <div class="recommend-module module-style">
                    <div class="rc-title">
                        相关视频推荐
                    </div>
                    <div class="rc-list row">
                        @foreach ($recommendMovies as $item)
                            <div class="rc-item col-lg-12 col-sm-6 clearfix">
                                <a href="/detail/{{ $Movie['movie_id'] }}" target='_blank' class="cover-wrapper">
                                    <div class="common-lazy-img">
                                        <img src="https://cdn-youku-com.diudie.com/app/image/image-5facd03dea2ea1.10509556.jpg"
                                            alt="">
                                    </div>
                                    <div class="video-mask"></div>
                                    <div class="duration">54:29:05</div>
                                </a>
                                <div class="info-wrapper">
                                    <a href="/detail/{{ $Movie['movie_id'] }}" target='_blank'
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
            </div>
        </div>
    </div>
@endsection
