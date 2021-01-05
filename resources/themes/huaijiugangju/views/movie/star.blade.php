@php
$movieCategory=array('电视剧'=>array_fill(0, 8, 'movieObject'),'电影'=>array_fill(0, 8, 'movieObject'),'综艺'=>array_fill(0, 8,
'movieObject'));
@endphp

@extends('layouts.app')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/star.css') }}">
@endpush

@section('title', '详情页')
@section('keywords', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')
@section('description', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')

@section('content')
    <div class="container-xl">
        <div class="row star-main">
            <div class="row star-top">
                <div class="col-lg-4 col-md-5  col-sm-6 col-xs-6 star-img">
                    <a class=" star-thumb lazyload-img" href="/star" title="石原里美"
                        style="background-image: url(https://www.hanjuwang.net/d/file/star/female/20171215/89adb8ae901a3c6fb230c8da47a05a8c.jpg);"></a>
                </div>
                <div class="col-lg-8 col-md-7  col-sm-6 col-xs-6 star-detail">
                    <h1 class="title text-ellipsis">金所炫</h1>
                    <ul>
                        <li><span class="meta">职业：</span>演员</li>
                        <li><span class="meta">出生时间：</span>1999年6月4日</li>
                        <li><span class="meta">昵称：</span>国民妹妹 ̖ 国民初恋 ̖ 国民甜心</li>
                        <li><span class="meta">教育程度：</span>汉阳大学戏剧电影学系(2018年入学)</li>
                        <li><span
                                class="meta">简介：</span>金所炫于1999年6月4日在澳大利亚出生。2003年时，金所炫的家庭移民回韩国,金所炫出道时签约SidusHQ，并于2007年开始参与剧集演出。在多部剧集中，因她饰演女主角的童年而为人所熟识。金所炫曾在《被破坏的男子（英语：Man
                            of Vendetta）》、《我们身边的犯罪》和荆棘鸟等电视剧和电影中客串。</li>
                    </ul>
                </div>
            </div>
            <div class="star-bottom">
                <h2>影视作品</h2>
                @foreach ($movieCategory as $category => $movies)
                    <h3>{{ $category }}</h3>
                    <div class="row movie-row">
                        @foreach ($movies as $movie)
                            <div class="col-2 padding-0">
                                @include('parts/movie.movie_item')
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
